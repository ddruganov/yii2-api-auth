FROM postgres:13.4-alpine

USER postgres

RUN chmod 0700 /var/lib/postgresql/data &&\
    initdb /var/lib/postgresql/data &&\
    echo "host all  all    0.0.0.0/0  md5" >> /var/lib/postgresql/data/pg_hba.conf &&\
    echo "listen_addresses='*'" >> /var/lib/postgresql/data/postgresql.conf &&\
    pg_ctl start &&\
    psql -U postgres -tc "SELECT 1 FROM pg_database WHERE datname = 'yii2apiauth'" | grep -q 1 || psql -U postgres -c "CREATE DATABASE yii2apiauth" &&\
    psql -U postgres -c "create user admin with encrypted password 'admin';" &&\
    psql -U postgres -c "grant all privileges on database yii2apiauth to admin;" &&\
    psql -c "ALTER USER postgres WITH ENCRYPTED PASSWORD 'admin';"