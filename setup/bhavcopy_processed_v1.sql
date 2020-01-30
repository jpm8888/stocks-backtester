insert into bhavcopy_processed (symbol, series, open, high, low, close, prevclose, volume, date, dlv_qty, pct_dlv_traded)
select bc.symbol, bc.series, bc.open, bc.high, bc.low, bc.close, bc.prevclose, bc.volume, bc.date, bdp.dlv_qty, bdp.pct_dlv_traded from bhavcopy_cm as bc
left join bhavcopy_delv_position as bdp on bc.symbol = bdp.symbol and bc.date = bdp.date and bc.series = bdp.series
where bc.id between 0 and 10000 and bc.symbol in (select symbol from master_stocks_fo)
and bc.series = 'EQ' and bdp.v1_processed = 0 limit 10000

update bhavcopy_delv_position set v1_processed = 1;

-- fast query
update bhavcopy_processed set price_change = ROUND(((close - prevclose) * 100) / prevclose, 2);

-- slow query
-- update bhavcopy_processed as bp set bp.cum_fut_oi = (select sum(bf.oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'FUTSTK');

UPDATE bhavcopy_processed bp inner join (select symbol, date, option_type, sum(oi) as total from bhavcopy_fo where option_type = "XX" group by symbol, date, option_type) x on bp.symbol = x.symbol and bp.date = x.date set bp.cum_fut_oi = x.total where bp.id between 1 and 20;



update bhavcopy_processed as bp set bp.change_cum_fut_oi_val = (select sum(bf.change_in_oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'FUTSTK');

update bhavcopy_processed as bp set bp.cum_pe_oi = (select sum(bf.oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'PE');

update bhavcopy_processed as bp set bp.cum_ce_oi = (select sum(bf.oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'CE');

update bhavcopy_processed set pcr = ROUND(cum_pe_oi / cum_ce_oi, 2);

update bhavcopy_processed as bp set bp.max_ce_oi_strike = (select bf.strike_price from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'CE' order by bf.oi desc limit 1);

update bhavcopy_processed as bp set bp.max_pe_oi_strike = (select bf.strike_price from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'PE' order by bf.oi desc limit 1);

update bhavcopy_processed as bp set bp.avg_volume_five = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 5) as vols), 0);

update bhavcopy_processed as bp set bp.avg_volume_ten = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 10) as vols), 0);

update bhavcopy_processed as bp set bp.avg_volume_fifteen = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 15) as vols), 0);

update bhavcopy_processed as bp set bp.avg_volume_fiftytwo = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 52) as vols), 0);

update bhavcopy_processed as bp set bp.low_five = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 5) as lows), 0);

update bhavcopy_processed as bp set bp.low_ten = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 10) as lows), 0);

update bhavcopy_processed as bp set bp.low_fifteen = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 15) as lows), 0);

update bhavcopy_processed as bp set bp.low_fiftytwo = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 52) as lows), 0);

update bhavcopy_processed set change_cum_fut_oi = ROUND((change_cum_fut_oi_val * 100) / (cum_fut_oi - (change_cum_fut_oi_val)), 2);

update bhavcopy_processed as bp set bp.change_cum_pe_oi_val = (select sum(bf.change_in_oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'PE');

update bhavcopy_processed as bp set bp.change_cum_ce_oi_val = (select sum(bf.change_in_oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'CE');

update bhavcopy_processed set change_cum_pe_oi = ROUND((change_cum_pe_oi_val * 100) / (cum_pe_oi - (change_cum_pe_oi_val)), 2);

update bhavcopy_processed set change_cum_ce_oi = ROUND((change_cum_ce_oi_val * 100) / (cum_ce_oi - (change_cum_ce_oi_val)), 2);

update bhavcopy_processed set v1_processed = 1;
