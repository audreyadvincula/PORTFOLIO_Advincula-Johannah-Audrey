import pandas as pd
from airflow.providers.postgres.hooks.postgres import PostgresHook
from sqlalchemy import text

# ==========================================
# HELPERS
# ==========================================
def get_staging_engine():
    return PostgresHook(postgres_conn_id="postgres_staging").get_sqlalchemy_engine()

def get_dw_engine():
    return PostgresHook(postgres_conn_id="postgres_dw").get_sqlalchemy_engine()

def run_dw_ddl(ddl_statements):
    """Executes DDL (ALTER, INSERT, ADD CONSTRAINT) on the DW."""
    engine = get_dw_engine()
    # Use begin() to auto-commit
    with engine.begin() as conn:
        for stmt in ddl_statements:
            if stmt.strip():
                try:
                    conn.execute(text(stmt))
                except Exception as e:
                    print(f"⚠️ Warning executing DDL: {e}")

# ==========================================
# 1. DIMENSION: USER
# ==========================================
def create_dim_user(**kwargs):
    print("--- Creating DIM_USER ---")
    dw_engine = get_dw_engine()
    
    # 1. DROP CASCADE (Fix for DependentObjectsStillExist)
    print("   > Dropping old table (CASCADE)...")
    # FIX: Use begin() and remove .commit()
    with dw_engine.begin() as conn:
        conn.execute(text("DROP TABLE IF EXISTS dw.dim_user CASCADE"))

    # 2. Extract Logic (Run on Staging)
    sql_select = """
    SELECT 
        ROW_NUMBER() OVER (ORDER BY u1."USER_ID") AS "USER_REFERENCE_NUMBER",
        u1."USER_ID", 
        u1."CREATION_DATE" AS "USER_CREATION_DATE", 
        u1."NAME" AS "USER_NAME", 
        u1."STREET" AS "USER_STREET", 
        u1."STATE" AS "USER_STATE",
        u1."CITY" AS "USER_CITY", 
        u1."COUNTRY" AS "USER_COUNTRY", 
        u1."BIRTHDATE" AS "USER_BIRTHDATE", 
        u1."GENDER" AS "USER_GENDER",
        u1."DEVICE_ADDRESS" AS "USER_DEVICE_ADDRESS",
        u1."USER_TYPE" AS "USER_TYPE",
        u2."JOB_TITLE" AS "USER_JOB_TITLE",
        u2."JOB_LEVEL" AS "USER_JOB_LEVEL",
        u3."CREDIT_CARD_NUMBER" AS "USER_CREDIT_CARD_NUMBER",
        u3."ISSUING_BANK" AS "USER_ISSUING_BANK"
    FROM customer_management_staging_cleaned.user_data_cleaned AS u1
    LEFT JOIN customer_management_staging_cleaned.user_job_cleaned AS u2
    ON u1."USER_ID" = u2."USER_ID"
    LEFT JOIN customer_management_staging_cleaned.user_credit_card_cleaned AS u3
    ON u1."USER_ID" = u3."USER_ID"
    """
    
    df = pd.read_sql(sql_select, get_staging_engine())
    
    # 3. Load Logic
    df.to_sql('dim_user', dw_engine, schema='dw', if_exists='append', index=False)
    
    # 4. Constraints
    ddl_scripts = [
        'ALTER TABLE dw.dim_user ADD CONSTRAINT dim_user_pk PRIMARY KEY ("USER_REFERENCE_NUMBER");',
        'ALTER TABLE dw.dim_user ALTER COLUMN "USER_REFERENCE_NUMBER" SET NOT NULL;',
        'ALTER TABLE dw.dim_user ALTER COLUMN "USER_ID" SET NOT NULL;'
    ]
    run_dw_ddl(ddl_scripts)
    print("✅ DIM_USER Created Successfully")

# ==========================================
# 2. DIMENSION: PRODUCT
# ==========================================
def create_dim_product(**kwargs):
    print("--- Creating DIM_PRODUCT ---")
    dw_engine = get_dw_engine()

    # DROP CASCADE (FIXED)
    with dw_engine.begin() as conn:
        conn.execute(text("DROP TABLE IF EXISTS dw.dim_product CASCADE"))

    sql_select = """
    SELECT 
        ROW_NUMBER() OVER (ORDER BY "PRODUCT_ID") AS "PRODUCT_REFERENCE_NUMBER",
        "PRODUCT_ID", 
        "PRODUCT_NAME", 
        "PRODUCT_TYPE"
    FROM business_staging_cleaned.product_list_cleaned
    """
    
    df = pd.read_sql(sql_select, get_staging_engine())
    df.to_sql('dim_product', dw_engine, schema='dw', if_exists='append', index=False)
    
    ddl_scripts = [
        'ALTER TABLE dw.dim_product ADD CONSTRAINT dim_product_pk PRIMARY KEY ("PRODUCT_REFERENCE_NUMBER");',
        'ALTER TABLE dw.dim_product ALTER COLUMN "PRODUCT_REFERENCE_NUMBER" SET NOT NULL;',
        'ALTER TABLE dw.dim_product ALTER COLUMN "PRODUCT_ID" SET NOT NULL;',
        "INSERT INTO dw.dim_product VALUES (-1, 'PRODUCT00000', 'No Product', 'No Product');"
    ]
    run_dw_ddl(ddl_scripts)
    print("✅ DIM_PRODUCT Created Successfully")

# ==========================================
# 3. DIMENSION: CAMPAIGN
# ==========================================
def create_dim_campaign(**kwargs):
    print("--- Creating DIM_CAMPAIGN ---")
    dw_engine = get_dw_engine()

    # DROP CASCADE (FIXED)
    with dw_engine.begin() as conn:
        conn.execute(text("DROP TABLE IF EXISTS dw.dim_campaign CASCADE"))

    sql_select = """
    SELECT 
        ROW_NUMBER() OVER (ORDER BY "CAMPAIGN_ID") AS "CAMPAIGN_REFERENCE_NUMBER",
        "CAMPAIGN_ID",
        "CAMPAIGN_NAME",
        "CAMPAIGN_DESCRIPTION",
        "DISCOUNT" AS "CAMPAIGN_DISCOUNT"
    FROM marketing_staging_cleaned.campaign_data_concat
    """
    
    df = pd.read_sql(sql_select, get_staging_engine())
    df.to_sql('dim_campaign', dw_engine, schema='dw', if_exists='append', index=False)
    
    ddl_scripts = [
        'ALTER TABLE dw.dim_campaign ADD CONSTRAINT dim_campaign_pk PRIMARY KEY ("CAMPAIGN_REFERENCE_NUMBER");',
        'ALTER TABLE dw.dim_campaign ALTER COLUMN "CAMPAIGN_REFERENCE_NUMBER" SET NOT NULL;',
        'ALTER TABLE dw.dim_campaign ALTER COLUMN "CAMPAIGN_ID" SET NOT NULL;',
        "INSERT INTO dw.dim_campaign VALUES (-1, 'CAMPAIGN00000', 'No Campaign', 'No Campaign', 0.0);"
    ]
    run_dw_ddl(ddl_scripts)
    print("✅ DIM_CAMPAIGN Created Successfully")

# ==========================================
# 4. DIMENSION: MERCHANT
# ==========================================
def create_dim_merchant(**kwargs):
    print("--- Creating DIM_MERCHANT ---")
    dw_engine = get_dw_engine()
    
    # DROP CASCADE (FIXED)
    with dw_engine.begin() as conn:
        conn.execute(text("DROP TABLE IF EXISTS dw.dim_merchant CASCADE"))

    sql_select = """
    SELECT
        ROW_NUMBER () OVER (ORDER BY "MERCHANT_ID") AS "MERCHANT_REFERENCE_NUMBER",
        "MERCHANT_ID",
        "NAME" AS "MERCHANT_NAME",
        "STREET" AS "MERCHANT_STREET",
        "CITY" AS "MERCHANT_CITY",
        "STATE" AS "MERCHANT_STATE",
        "COUNTRY" AS "MERCHANT_COUNTRY",
        "CONTACT_NUMBER" AS "MERCHANT_CONTACT_NUMBER",
        "CREATION_DATE" AS "MERCHANT_CREATION_DATE"
    FROM enterprise_staging_cleaned.merchant_data_cleaned
    """
    
    df = pd.read_sql(sql_select, get_staging_engine())
    df.to_sql('dim_merchant', dw_engine, schema='dw', if_exists='append', index=False)
    
    ddl_scripts = [
        'ALTER TABLE dw.dim_merchant ADD CONSTRAINT dim_merchant_pk PRIMARY KEY ("MERCHANT_REFERENCE_NUMBER");',
        'ALTER TABLE dw.dim_merchant ALTER COLUMN "MERCHANT_REFERENCE_NUMBER" SET NOT NULL;',
        'ALTER TABLE dw.dim_merchant ALTER COLUMN "MERCHANT_ID" SET NOT NULL;'
    ]
    run_dw_ddl(ddl_scripts)
    print("✅ DIM_MERCHANT Created Successfully")

# ==========================================
# 5. DIMENSION: STAFF
# ==========================================
def create_dim_staff(**kwargs):
    print("--- Creating DIM_STAFF ---")
    dw_engine = get_dw_engine()
    
    # DROP CASCADE (FIXED)
    with dw_engine.begin() as conn:
        conn.execute(text("DROP TABLE IF EXISTS dw.dim_staff CASCADE"))

    sql_select = """
    SELECT
        ROW_NUMBER () OVER (ORDER BY "STAFF_ID") AS "STAFF_REFERENCE_NUMBER", 
        "STAFF_ID",
        "NAME" AS "STAFF_NAME",
        "JOB_LEVEL" AS "STAFF_JOB_LEVEL",
        "STREET" AS "STAFF_STREET",
        "CITY" AS "STAFF_CITY",
        "STATE" AS "STAFF_STATE", 
        "COUNTRY" AS "STAFF_COUNTRY",
        "CONTACT_NUMBER" AS "STAFF_CONTACT_NUMBER",
        "CREATION_DATE" AS "STAFF_CREATION_DATE"
    FROM enterprise_staging_cleaned.staff_data_cleaned
    """
    
    df = pd.read_sql(sql_select, get_staging_engine())
    df.to_sql('dim_staff', dw_engine, schema='dw', if_exists='append', index=False)
    
    ddl_scripts = [
        'ALTER TABLE dw.dim_staff ADD CONSTRAINT dim_staff_pk PRIMARY KEY ("STAFF_REFERENCE_NUMBER");',
        'ALTER TABLE dw.dim_staff ALTER COLUMN "STAFF_REFERENCE_NUMBER" SET NOT NULL;',
        'ALTER TABLE dw.dim_staff ALTER COLUMN "STAFF_ID" SET NOT NULL;'
    ]
    run_dw_ddl(ddl_scripts)
    print("✅ DIM_STAFF Created Successfully")


# ==========================================
# 6. FACT: ORDER (Optimized with FDW / SQL)
# ==========================================

def create_fact_order(**kwargs):
    print("--- Creating FACT_ORDER via FDW (SQL) ---")
    
    hook = PostgresHook(postgres_conn_id="postgres_dw")
    engine = hook.get_sqlalchemy_engine()
    
    sql_script = """
    CREATE EXTENSION IF NOT EXISTS postgres_fdw;

    DROP SERVER IF EXISTS staging_server CASCADE;
    
    CREATE SERVER staging_server 
        FOREIGN DATA WRAPPER postgres_fdw 
        OPTIONS (host 'postgres-staging', port '5432', dbname 'shopzada');

    CREATE USER MAPPING IF NOT EXISTS FOR CURRENT_USER
        SERVER staging_server 
        OPTIONS (user 'airflow', password 'airflow');

    DROP SCHEMA IF EXISTS staging_proxy CASCADE;
    CREATE SCHEMA staging_proxy;

    DO $$
    BEGIN
        BEGIN
            IMPORT FOREIGN SCHEMA operation_staging_cleaned 
            FROM SERVER staging_server INTO staging_proxy;
        EXCEPTION WHEN duplicate_table THEN NULL; END;

        BEGIN
            IMPORT FOREIGN SCHEMA marketing_staging_cleaned 
            FROM SERVER staging_server INTO staging_proxy;
        EXCEPTION WHEN duplicate_table THEN NULL; END;

        BEGIN
            IMPORT FOREIGN SCHEMA enterprise_staging_cleaned 
            FROM SERVER staging_server INTO staging_proxy;
        EXCEPTION WHEN duplicate_table THEN NULL; END;

        BEGIN
            IMPORT FOREIGN SCHEMA business_staging_cleaned 
            FROM SERVER staging_server INTO staging_proxy;
        EXCEPTION WHEN duplicate_table THEN NULL; END;
        
        BEGIN
            IMPORT FOREIGN SCHEMA customer_management_staging_cleaned 
            FROM SERVER staging_server INTO staging_proxy;
        EXCEPTION WHEN duplicate_table THEN NULL; END;
    END;
    $$;    
    
    CREATE SCHEMA IF NOT EXISTS dw;
    
    -- DROP CASCADE (Fixed for views)
    DROP TABLE IF EXISTS dw.fact_order CASCADE;
    
    CREATE TABLE dw.fact_order AS
    SELECT
        ROW_NUMBER () OVER (ORDER BY o.order_id) AS "ORDER_REFERENCE_NUMBER",
        u."USER_REFERENCE_NUMBER",
        CASE 
            WHEN product."PRODUCT_REFERENCE_NUMBER" IS NULL
            THEN -1
            ELSE product."PRODUCT_REFERENCE_NUMBER" END AS "PRODUCT_REFERENCE_NUMBER",
        CASE 
            WHEN campaign."CAMPAIGN_REFERENCE_NUMBER" IS NULL 
            THEN -1 
            ELSE campaign."CAMPAIGN_REFERENCE_NUMBER" END AS "CAMPAIGN_REFERENCE_NUMBER",
        staff."STAFF_REFERENCE_NUMBER",
        merchant."MERCHANT_REFERENCE_NUMBER",
        o.order_id AS "ORDER_ID",
        o."estimated arrival" AS "ORDER_ESTIMATED_ARRIVAL", 
        o.transaction_date AS "ORDER_TRANSACTION_DATE",
        COALESCE(l1.price, 0) AS "ORDER_PRICE",
        COALESCE(l1.quantity, 0) AS "ORDER_QUANTITY",
        COALESCE(d."delay in days", 0) AS "DELAY_IN_DAYS",
        CASE 
            WHEN t."AVAILED"::text = '1' THEN 'Availed'
            WHEN t."AVAILED"::text = '0' THEN 'Not Availed'
            ELSE 'Not Applicable' 
        END AS "ORDER_W_PROMO"
    FROM staging_proxy.order_data_concat AS o
    LEFT JOIN staging_proxy.line_item_data_prices_concat AS l1  
    ON o.order_id = l1.order_id
    LEFT JOIN staging_proxy.line_item_data_products_concat AS l2
    ON o.order_id = l2.order_id
    LEFT JOIN staging_proxy.order_delays_cleaned AS d
    ON o.order_id = d.order_id
    LEFT JOIN staging_proxy.transactional_campaign_data_concat AS t
    ON o.order_id = t."ORDER_ID"
    LEFT JOIN dw.dim_user AS u
    ON o.user_id = u."USER_ID"
    LEFT JOIN dw.dim_product AS product
    ON l2.product_id = product."PRODUCT_ID"
    LEFT JOIN dw.dim_campaign AS campaign
    ON t."CAMPAIGN_ID" = campaign."CAMPAIGN_ID" 
    LEFT JOIN staging_proxy.order_with_merchant_data_concat AS oms
    ON o.order_id = oms."ORDER_ID"
    LEFT JOIN dw.dim_staff AS staff
    ON oms."STAFF_ID" = staff."STAFF_ID"
    LEFT JOIN dw.dim_merchant AS merchant
    ON oms."MERCHANT_ID" = merchant."MERCHANT_ID";
    
    ALTER TABLE dw.fact_order ADD CONSTRAINT fact_order_pk PRIMARY KEY ("ORDER_REFERENCE_NUMBER");
    """

    print("   > Executing SQL Transformation...")
    # FIX: Use begin() for FDW logic
    with engine.begin() as conn:
        conn.execute(text(sql_script))
    
    print("✅ FACT_ORDER Created Successfully via SQL.")


# ==========================================
# 7. MATERIALIZED VIEWS (Recreate after Drop Cascade)
# ==========================================

def create_materialized_views(**kwargs):
    print("--- Recreating Materialized Views ---")
    hook = PostgresHook(postgres_conn_id="postgres_dw")
    engine = hook.get_sqlalchemy_engine()
    
    # Store queries in a list instead of a single string
    view_queries = [
        # 1. Orders
        """
        CREATE MATERIALIZED VIEW IF NOT EXISTS dw.orders_matview AS
        SELECT 
            "ORDER_REFERENCE_NUMBER",
            "USER_REFERENCE_NUMBER",
            "PRODUCT_REFERENCE_NUMBER",
            "CAMPAIGN_REFERENCE_NUMBER",
            "STAFF_REFERENCE_NUMBER",
            "MERCHANT_REFERENCE_NUMBER",
            "ORDER_ID",
            "ORDER_ESTIMATED_ARRIVAL",
            "ORDER_TRANSACTION_DATE",
            "ORDER_PRICE",
            "ORDER_QUANTITY",
            "DELAY_IN_DAYS",
            "ORDER_W_PROMO"
        FROM dw.fact_order;
        """,
        
        # 2. Campaign
        """
        CREATE MATERIALIZED VIEW IF NOT EXISTS dw.campaign_matview AS
        SELECT 
            "CAMPAIGN_REFERENCE_NUMBER",
            "CAMPAIGN_ID",
            "CAMPAIGN_NAME",
            "CAMPAIGN_DESCRIPTION",
            "CAMPAIGN_DISCOUNT"
        FROM dw.dim_campaign;
        """,

        # 3. Merchant
        """
        CREATE MATERIALIZED VIEW IF NOT EXISTS dw.merchant_matview AS
        SELECT 
            "MERCHANT_REFERENCE_NUMBER",
            "MERCHANT_ID",
            "MERCHANT_NAME",
            "MERCHANT_STREET",
            "MERCHANT_CITY",
            "MERCHANT_STATE",
            "MERCHANT_COUNTRY",
            "MERCHANT_CONTACT_NUMBER",
            "MERCHANT_CREATION_DATE"
        FROM dw.dim_merchant;
        """,

        # 4. Product
        """
        CREATE MATERIALIZED VIEW IF NOT EXISTS dw.product_matview AS
        SELECT 
            "PRODUCT_REFERENCE_NUMBER",
            "PRODUCT_ID",
            "PRODUCT_NAME",
            "PRODUCT_TYPE"
        FROM dw.dim_product;
        """,

        # 5. User
        """
        CREATE MATERIALIZED VIEW IF NOT EXISTS dw.user_matview AS
        SELECT
            "USER_REFERENCE_NUMBER",
            "USER_ID",
            "USER_CREATION_DATE",
            "USER_NAME",
            "USER_STREET",
            "USER_STATE",
            "USER_CITY",
            "USER_COUNTRY",
            "USER_BIRTHDATE",
            "USER_GENDER",
            "USER_DEVICE_ADDRESS",
            "USER_TYPE",
            "USER_JOB_TITLE",
            "USER_JOB_LEVEL",
            "USER_CREDIT_CARD_NUMBER",
            "USER_ISSUING_BANK"
        FROM dw.dim_user;
        """,

        # 6. Staff
        """
        CREATE MATERIALIZED VIEW IF NOT EXISTS dw.staff_matview AS
        SELECT
            "STAFF_REFERENCE_NUMBER",
            "STAFF_ID",
            "STAFF_NAME",
            "STAFF_JOB_LEVEL",
            "STAFF_STREET",
            "STAFF_CITY",
            "STAFF_STATE",
            "STAFF_COUNTRY",
            "STAFF_CONTACT_NUMBER",
            "STAFF_CREATION_DATE"
        FROM dw.dim_staff;
        """
    ]
    
    # Use begin() to start a transaction
    with engine.begin() as conn:
        for query in view_queries:
            # strip() removes accidental whitespace usually caused by multi-line strings
            conn.execute(text(query.strip()))
        
    print("✅ Materialized Views Restored.")