#update change_cum_fut_oi_val
limit=10000;
min=0;
max=$limit;
for (( c=1; c<=50; c++ ))
do
   query="UPDATE bhavcopy_processed bp inner join (select symbol, date, option_type, sum(change_in_oi) as total from bhavcopy_fo where option_type = 'XX' group by symbol, date, option_type) x on bp.symbol = x.symbol and bp.date = x.date set bp.change_cum_fut_oi_val = x.total where bp.id between $min and $max;";

   command="/usr/local/apps/mysql/bin/mysql -u test_user -pjpm19901 isl_shcema -e \"$query\" >> updates.log";
   echo  $command >> insert.sh;
   echo "echo '$max records...: '" >> insert.sh;

   min=$(expr "$max" + 1);
   max=$(expr "$max" '+' "$limit");
done
chmod a+x insert.sh;

