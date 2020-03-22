import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import ComponentFileInput from "../../common/ComponentFileInput";
import ComponentCard from "../../common/ComponentCard";
import ComponentRow from "../../common/ComponentRow";
import {upload_file} from "../actions/uploadActions";
import ComponentAlert from "../../common/ComponentAlert";

class UploadView extends Component {
    render() {
        return (
            <div>
                <ComponentCard label={'Upload File'} loading={this.props.isUploading}>
                    <ComponentAlert type={(this.props.error) ? 'danger' : 'success'} msg={this.props.msg}/>
                    <ComponentRow>
                        <ComponentFileInput label={'CSV File'} required={true} onChange={(e) =>this.props.upload_file(e.target.files[0])} hint={'File size should be less than 500 KB'}/>
                    </ComponentRow>
                </ComponentCard>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    const s = state.uploadReducer;
    return {
        isUploading: s.isUploading,
        file : s.file,
        error : s.error,
        msg : s.msg,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        upload_file : (file) => upload_file(file),
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(UploadView);

