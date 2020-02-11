filename='rebuild.sh'
username='user'
password='password'
database='databasename'
for (( c=2008; c<=2020; c++ ))
do
   query="update bhavcopy_processed partition (p_$c) set v1_processed = 0;";
   command="mysql -u $username -p$password $database -e \"$query\"";
   echo  $command >> $filename;
   echo "php artisan process:bhavcopy_v1 $c" >> $filename
   echo  'echo $(date) : rebuild done on partition_' $c ' : done.' >> $filename;

done
echo  'echo $(date) : all done.' >> $filename;
chmod a+x $filename;
