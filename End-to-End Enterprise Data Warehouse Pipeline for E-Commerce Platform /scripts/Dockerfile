# Base Image
FROM apache/airflow:2.10.3

ENV AIRFLOW_HOME=/opt/airflow

# Airflow User
USER airflow

# Python Dependencies
RUN pip install --no-cache-dir \
    numpy \
    pandas \
    pyarrow \
    fastparquet \
    openpyxl \
    psycopg2-binary \
    sqlalchemy \
    lxml \
    html5lib \

    beautifulsoup4
