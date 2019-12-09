import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import {fetch_params} from "../actions/filterActions";
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
                        <ComponentMultiSelect label={"BANKS"} options={this.props.banknifty}/>
                        <ComponentMultiSelect label={"BNF"} options={this.props.banknifty}/>
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
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        fetch_params : () => fetch_params(),
    },dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(Filter);

