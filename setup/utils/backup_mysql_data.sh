echo "$(date) : starting backup..."

mysqlDumpPath="/usr/local/apps/mysql/bin/mysqldump";
username='test_user';
password='jpm19901'
databaseName="isl_shcema";

make_backup() {
    table_name="$1";
    filename="$table_name-$(date +%Y-%m-%d_%H-%M-%S)";
    echo $(date) : creating backup $filename;
    $mysqlDumpPath -u $username -p$password  --skip-triggers --compact --no-create-info $databaseName $table_name | gzip > $filename.sql.gz
    echo $(date) : $table_name "backup done" >> backup_logs.log;
}

make_backup bhavcopy_cm
make_backup bhavcopy_delv_position
make_backup bhavcopy_fo
make_backup bhavcopy_processed
make_backup master_stocks_fo

echo "$(date) : all done for now..."
