import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import {fetch_params, on_change_index, on_change_symbol_select} from "../actions/filterActions";
import ComponentContainer from "../../common/ComponentContainer";
import ComponentRow from "../../common/ComponentRow";
import ComponentMultiSelect from "../../common/ComponentMultiSelect";
import ComponentCard from "../../common/ComponentCard";
import ComponentTable from "../../common/Table/ComponentTable";
import Tr from "../../common/Table/Tr";
import Th from "../../common/Table/Th";
import Td from "../../common/Table/Td";

class Filter extends Component {

    constructor(props){
        super(props);
        this.props.fetch_params();
    }


    render() {
        const symbols = this.props.symbols;
        const data = this.props.data;

        return (
            <ComponentContainer>
                <ComponentCard label={'Filter'} loading={this.props.is_loading}>
                    <ComponentRow>
                        <ComponentMultiSelect value={this.props.index} label={"Index"} options={this.props.indices} onChange={(s)=> this.props.on_change_index(s)}/>
                        <ComponentMultiSelect value={this.props.s_stock} label={"Symbols"} options={symbols} onChange={(s)=> this.props.on_change_symbol_select(s)}/>
                    </ComponentRow>
                </ComponentCard>
                <ComponentCard label={'Data'}>
                    <ComponentRow>
                        <ComponentTable>
                            <Tr>
                                <Th>Date</Th>
                                <Th>Symbol</Th>
                                <Th>o</Th>
                                <Th>h</Th>
                                <Th>l</Th>
                                <Th>c</Th>
                                <Th>Trade Val (in Cr.)</Th>
                                <Th>Total Trades</Th>
                                <Th>Volume</Th>
                                <Th>5-D Avg Vol</Th>
                                <Th>Dlv Qty (in Lacs)</Th>
                                <Th>Dlv Value (in Cr.)</Th>
                                <Th>5-D Avg Dlv Value (in Cr.)</Th>
                                <Th>%Dlv Qty</Th>
                                <Th>%5-D Avg Vol</Th>
                                <Th>Chng. Price</Th>
                                <Th>%5-D Avg. Dlv</Th>
                                <Th>FUT COI</Th>
                                <Th>FUT Chng. COI</Th>
                                <Th>FUT %Chng. COI</Th>
                                {/*<Th>CE COI</Th>*/}
                                {/*<Th>%Chng. CE COI</Th>*/}
                                {/*<Th>PE COI</Th>*/}
                                {/*<Th>%Chng. PE COI</Th>*/}
                            </Tr>
                            {
                                data.map((item, index)=>{
                                   return (
                                     <Tr key={'item_' + index}>
                                         <Td>{item.f_date}</Td>
                                         <Td>{item.symbol}</Td>
                                         <Td>{item.open}</Td>
                                         <Td>{item.high}</Td>
                                         <Td>{item.low}</Td>
                                         <Td>{item.close}</Td>
                                         <Td>{item.f_traded_value}</Td>
                                         <Td>{item.total_trades}</Td>
                                         <Td>{item.volume}</Td>
                                         <Td>{item.f_five_day_volume_avg.avg_volume}</Td>
                                         <Td>{Math.round(item.dlv_qty / 100000)}</Td>
                                         <Td>{item.f_dlv_in_crores}</Td>
                                         <Td>{item.f_avg_dlv_in_crores}</Td>
                                         <Td>{item.pct_dlv_traded + ' %'}</Td>
                                         <FiveDayAvgPercentChange value={item.f_five_day_volume_avg.change}/>
                                         <PriceChange value={item.f_price_change}/>
                                         <FiveDayAvgPercentChange value={item.del_pct_five_day}/>
                                         <Td>{item.f_cum_fut_oi}</Td>
                                         <Td>{item.f_change_cum_fut_oi.coi}</Td>
                                         <PriceChange value={item.f_change_cum_fut_oi.coi_pct}/>
                                         {/*<Td>{item.f_option_data.today_cum_ce_oi}</Td>*/}
                                         {/*<PriceChange value={item.f_option_data.coi_pct_ce}/>*/}
                                         {/*<Td>{item.f_option_data.today_cum_pe_oi}</Td>*/}
                                         {/*<PriceChange value={item.f_option_data.coi_pct_pe}/>*/}
                                     </Tr>
                                   );
                                })
                            }
                        </ComponentTable>
                    </ComponentRow>
                </ComponentCard>
            </ComponentContainer>
        );
    }
}

const PriceChange = (props) =>{
    const value = props.value;
    let fpc_bg_color = 'white';
    let fpc_color = 'black';
    if (value >= 1.5){
        fpc_bg_color = '#c6efce';
        fpc_color = '#096709';
    }

    if (value <= -1.5){
        fpc_bg_color = '#ffc7ce';
        fpc_color = '#a0090e';
    }

    return (
        <Td backgroundColor={fpc_bg_color} color={fpc_color}>{value + ' %'}</Td>
    );
}

const FiveDayAvgPercentChange = (props) =>{
    const value = props.value;
    let fpc_bg_color = 'white';
    let fpc_color = 'black';
    if (value >= 100 || value <= -100){
        fpc_bg_color = '#ffeb9c';
        fpc_color = '#9c6527';
    }

    return (
        <Td backgroundColor={fpc_bg_color} color={fpc_color}>{value + ' %'}</Td>
    );
}

const mapStateToProps = (state) => {
    const s = state.filterReducer;
    return {
        indices : s.indices,
        is_loading : s.is_loading,

        symbols : s.symbols,

        s_stock : s.s_stock,
        s_symbol : s.s_symbol,

        data : s.data,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        fetch_params : () => fetch_params(),
        on_change_index  : (selected) => on_change_index(selected),
        on_change_symbol_select  : (selected) => on_change_symbol_select(selected),
    },dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(Filter);

