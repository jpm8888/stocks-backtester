import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import ComponentContainer from "../../common/ComponentContainer";
import ComponentRow from "../../common/ComponentRow";
import ComponentCard from "../../common/ComponentCard";
import ComponentInput from "../../common/ComponentInput";
import {on_change} from "../actions/indexActions";
import ComponentTable from "../../common/Table/ComponentTable";
import Tr from "../../common/Table/Tr";
import Th from "../../common/Table/Th";
import Td from "../../common/Table/Td";


class CalculatorIndex extends Component {

    constructor(props){
        super(props);
    }


    render() {
        return (
            <ComponentContainer>
                <ComponentCard label={'Filter'} loading={this.props.is_loading}>
                    <ComponentRow>
                        <ComponentInput type="number" step="0.01" value={this.props.capital_amount} label={"Capital Amount"} onChange={(e)=> this.props.on_change("capital_amount", e.target.value)}/>
                        <ComponentInput type="number" step="0.01" value={this.props.entry} label={"Entry"} onChange={(e)=> this.props.on_change("entry", e.target.value)}/>
                        <ComponentInput type="number" step="0.01" value={this.props.stop_loss} label={"Stop Loss"} onChange={(e)=> this.props.on_change("stop_loss", e.target.value)}/>
                    </ComponentRow>

                    <ComponentTable>
                        <Tr>
                            <Th>Amount</Th>
                            <Th>Entry</Th>
                            <Th>SL</Th>
                            <Th>Risk %</Th>
                            <Th>Target %</Th>
                            <Th>Target Amount</Th>
                            <Th>QTY</Th>
                            <Th>Expected Profit</Th>
                            <Th>Expected Loss</Th>
                        </Tr>

                        <Tr>
                            <Td>{this.props.capital_amount}</Td>
                            <Td>{this.props.entry}</Td>
                            <Td>{this.props.stop_loss}</Td>
                            <Td>{this.props.risk_pct + " %"}</Td>
                            <Td>{this.props.target_pct + " %"}</Td>
                            <Td>{this.props.target_amount}</Td>
                            <Td>{this.props.qty}</Td>
                            <Td>{this.props.expected_profit}</Td>
                            <Td>{this.props.expected_loss}</Td>
                        </Tr>

                    </ComponentTable>
                </ComponentCard>
            </ComponentContainer>
        );
    }
}


const mapStateToProps = (state) => {
    const s = state.indexReducer;
    return {
        capital_amount : s.capital_amount,
        entry : s.entry,
        stop_loss : s.stop_loss,
        risk_pct : s.risk_pct,
        target_pct : s.target_pct,
        target_amount : s.target_amount,
        qty : s.qty,
        expected_profit : s.expected_profit,
        expected_loss : s.expected_loss,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        on_change  : (name, value) => on_change(name, value),
    },dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(CalculatorIndex);

