import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import {fetch_params, on_change_bnf_select} from "../actions/filterActions";
import ComponentContainer from "../../common/ComponentContainer";
import ComponentRow from "../../common/ComponentRow";
import ComponentMultiSelect from "../../common/ComponentMultiSelect";
import ComponentCard from "../../common/ComponentCard";

class Filter extends Component {

    constructor(props){
        super(props);
        this.props.fetch_params();
    }


    render() {
        const banknifty = this.props.banknifty;

        return (
            <ComponentContainer>
                <ComponentCard>
                    <ComponentRow>
                        <ComponentMultiSelect value={this.props.s_stock} label={"BANKS"} options={banknifty} onChange={(s)=> this.props.on_change_bnf_select(s)}/>
                    </ComponentRow>
                </ComponentCard>
            </ComponentContainer>
        );
    }
}


const mapStateToProps = (state) => {
    const s = state.filterReducer;
    return {
        is_loading : s.is_loading,
        banknifty : s.banknifty,
        s_stock : s.s_stock,
        s_symbol : s.s_symbol,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        fetch_params : () => fetch_params(),
        on_change_bnf_select  : (selected) => on_change_bnf_select(selected)
    },dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(Filter);

