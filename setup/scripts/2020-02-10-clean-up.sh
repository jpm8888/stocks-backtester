filename='clean_up.sh'
username='xyz'
password='password'
database='db_name'
for (( c=2008; c<=2020; c++ ))
do
   query="delete from bhavcopy_fo partition (p_$c) where oi = 0 and change_in_oi = 0;";
   command="mysql -u $username -p$password $database -e \"$query\"";
   echo  $command >> $filename;

   echo  'echo $(date) : partition_' $c ' : done.' >> $filename;

done
echo  'echo $(date) : all done.' >> $filename;
chmod a+x $filename;
