
#for backing up from image server.
#it'll only generate sql insert queries;
./mysqldump -u test_user -p  --skip-triggers --compact --no-create-info isl_shcema | gzip >  /home/ccellrpf/Videos/temp/15012020_1547.sql.gz

