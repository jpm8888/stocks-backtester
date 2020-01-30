#update change_cum_ce_oi_val
filename="ce_oi_calc.sh"
logname="change_cum_ce_oi_val"
limit=10000;
min=0;
max=$limit;
> $filename; #clear contents of the file;
for (( c=1; c<=50; c++ ))
do
   query="UPDATE bhavcopy_processed bp inner join (select symbol, date, option_type, sum(change_in_oi) as total from bhavcopy_fo where option_type = 'CE' group by symbol, date, option_type) x on bp.symbol = x.symbol and bp.date = x.date set bp.change_cum_ce_oi_val = x.total where bp.id between $min and $max;";

   command="/usr/local/apps/mysql/bin/mysql -u test_user -pjpm19901 isl_shcema -e \"$query\" >> updates.log";
   echo  $command >> $filename;
   echo "echo '$logname : $max records...: '" >> $filename;

   min=$(expr "$max" + 1);
   max=$(expr "$max" '+' "$limit");
done

#update cum_ce_oi
logname="cum_ce_oi"
limit=10000;
min=0;
max=$limit;
for (( c=1; c<=50; c++ ))
do
   query="UPDATE bhavcopy_processed bp inner join (select symbol, date, option_type, sum(oi) as total from bhavcopy_fo where option_type = 'CE' group by symbol, date, option_type) x on bp.symbol = x.symbol and bp.date = x.date set bp.cum_ce_oi = x.total where bp.id between $min and $max;";

   command="/usr/local/apps/mysql/bin/mysql -u test_user -pjpm19901 isl_shcema -e \"$query\" >> updates.log";
   echo  $command >> $filename;
   echo "echo '$logname : $max records...: '" >> $filename;

   min=$(expr "$max" + 1);
   max=$(expr "$max" '+' "$limit");
done


chmod a+x $filename;

