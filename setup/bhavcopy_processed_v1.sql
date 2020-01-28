insert into bhavcopy_processed (symbol, series, open, high, low, close, prevclose, volume, date, dlv_qty, pct_dlv_traded) 
	select msf.symbol, bc.series, bc.open, bc.high, bc.low, bc.close, bc.prevclose, bc.volume, bc.date, bdp.dlv_qty, bdp.pct_dlv_traded from master_stocks_fo as msf 
	left join bhavcopy_cm as bc on msf.symbol = bc.symbol 
	left join bhavcopy_delv_position as bdp on bc.symbol = bdp.symbol and bc.date = bdp.date and bc.series = bdp.series
	limit 10;

update bhavcopy_processed set price_change = ROUND(((close - prevclose) * 100) / prevclose, 2);


update bhavcopy_processed as bp set bp.cum_fut_oi = (select sum(bf.oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'FUTSTK');

update bhavcopy_processed as bp set bp.cum_pe_oi = (select sum(bf.oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'PE');

update bhavcopy_processed as bp set bp.cum_ce_oi = (select sum(bf.oi) from bhavcopy_fo as bf where bf.symbol = bp.symbol and bf.date = bp.date and bf.instrument = 'OPTSTK' and bf.option_type = 'CE');

update bhavcopy_processed set pcr = ROUND(cum_pe_oi / cum_ce_oi, 2);
