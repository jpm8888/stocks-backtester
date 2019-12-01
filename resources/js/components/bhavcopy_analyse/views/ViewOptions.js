import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import {fetch_params} from "../actions/optionsActions";
import ComponentSelect from "../../common/ComponentSelect";
import ComponentContainer from "../../common/ComponentContainer";
import ComponentRow from "../../common/ComponentRow";

class ViewOptions extends Component {

    constructor(props){
        super(props);
        this.props.fetch_params();
    }


    render() {
        const options = [
            {id : 1, name : 'Hello 1'},
            {id : 2, name : 'Hello 2'},
            {id : 3, name : 'Hello 3'},
            {id : 4, name : 'Hello 4'},
        ];
        return (
            <ComponentContainer>
                <ComponentRow>
                    <ComponentSelect label={'Expiry'} options={options}/>
                </ComponentRow>
            </ComponentContainer>
        );
    }
}


const mapStateToProps = (state) => {
    // const m_state = state.application_view;
    // const m_revert_to_admin_state = state.revert_to_admin_view;
    return {
        // view_id : m_state.view_id,
        // remark : m_revert_to_admin_state.remark,
        // is_fetching : m_revert_to_admin_state.is_fetching,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        fetch_params : () => fetch_params(),
        // revert_to_admin_application : (id, remark) => revert_to_admin_application(id, remark),
        // on_update_remark : (remark) => on_update_revert_to_admin_remark(remark),
    },dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(ViewOptions);

