limit=10000;
min=0;
max=$limit;
for (( c=1; c<=500; c++ ))
do
   query="insert into bhavcopy_processed (symbol, series, open, high, low, close, prevclose, volume, date, dlv_qty, pct_dlv_traded)";
   query="${query} select bc.symbol, bc.series, bc.open, bc.high, bc.low, bc.close, bc.prevclose, bc.volume, bc.date, bdp.dlv_qty, bdp.pct_dlv_traded from bhavcopy_cm as bc left join bhavcopy_delv_position as bdp on bc.symbol = bdp.symbol and bc.date = bdp.date and bc.series = bdp.series where bc.id between $min and $max and bc.symbol in (select symbol from master_stocks_fo) and bc.series = 'EQ' limit $limit";

   command="/usr/local/apps/mysql/bin/mysql -u test_user -pjpm19901 isl_shcema -e \"$query\"";
   echo  $command >> insert.sh;
   echo "echo '$max records...'" >> insert.sh;

   min=$(expr "$max" + 1);
   max=$(expr "$max" '+' "$limit");
done
chmod a+x insert.sh;
