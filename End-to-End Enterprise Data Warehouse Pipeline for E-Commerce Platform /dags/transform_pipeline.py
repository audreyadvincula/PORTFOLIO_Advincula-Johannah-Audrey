from airflow import DAG
from airflow.operators.python import PythonOperator
from datetime import datetime

# Import the transformation functions
from auto_script.transformation_functions import (
    create_dim_user,
    create_dim_product,
    create_dim_campaign,
    create_dim_merchant,
    create_dim_staff,
    create_fact_order,
    create_materialized_views  # <--- NEW IMPORT
)

# ==========================================
#             DAG CONFIGURATION
# ==========================================
START_DATE = datetime(2023, 1, 1)

with DAG(
    'unified_transformation_dag',
    start_date=START_DATE,
    schedule_interval=None, 
    catchup=False,
    tags=['dw', 'transformation', 'star_schema', 'merging']
) as dag:

    # ==========================================
    # PHASE 1: DIMENSION TABLES (Parallel)
    # ==========================================
    
    t_dim_user = PythonOperator(
        task_id='create_dim_user',
        python_callable=create_dim_user
    )

    t_dim_prod = PythonOperator(
        task_id='create_dim_product',
        python_callable=create_dim_product
    )

    t_dim_camp = PythonOperator(
        task_id='create_dim_campaign',
        python_callable=create_dim_campaign
    )

    t_dim_merch = PythonOperator(
        task_id='create_dim_merchant',
        python_callable=create_dim_merchant
    )

    t_dim_staff = PythonOperator(
        task_id='create_dim_staff',
        python_callable=create_dim_staff
    )

    # ==========================================
    # PHASE 2: FACT TABLE (Dependent)
    # ==========================================
    
    t_fact_order = PythonOperator(
        task_id='create_fact_order',
        python_callable=create_fact_order
    )

    # ==========================================
    # PHASE 3: MATERIALIZED VIEWS (New)
    # ==========================================
    
    t_create_views = PythonOperator(
        task_id='create_materialized_views',
        python_callable=create_materialized_views
    )

    # ==========================================
    # DEPENDENCIES
    # ==========================================
    # 1. Create all Dimensions
    # 2. Create Fact Table (reads from Dimensions)
    # 3. Create Views (reads from Fact & Dimensions)
    

    [t_dim_user, t_dim_prod, t_dim_camp, t_dim_merch, t_dim_staff] >> t_fact_order >> t_create_views
