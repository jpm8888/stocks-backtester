import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import ComponentCard from "../../common/ComponentCard";

class LogView extends Component {
    render() {
        return (
            <div>
                <ComponentCard label={'Logs'}>
                    <ul>
                        <li><code style={{color : 'green'}}>Total Imported Records : {this.props.importedRecords}</code></li>
                        {
                            this.props.errorLogs.map((item, idx) =>{
                              return <li key={'idx_' + idx}><code style={{color : 'red'}}>{`At Index : ${item.idx}, Error : ${item.error}`}</code></li>
                            })
                        }
                    </ul>
                </ComponentCard>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    const s = state.importReducer;
    return {
        importedRecords: s.importedRecords,
        errorLogs : s.errorLogs,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        // upload_file : (file) => upload_file(file),
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(LogView);

