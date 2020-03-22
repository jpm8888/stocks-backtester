import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import ComponentCard from "../../common/ComponentCard";
import ComponentRow from "../../common/ComponentRow";
import ComponentAlert from "../../common/ComponentAlert";
import {
    fetch_map,
    fetch_table_names,
    onSelectDatatype,
    onSelectMap,
    onSelectTable,
    onUpload
} from "../actions/importActions";
import ComponentLoading from "../../common/ComponentLoading";
import ComponentSelect from "../../common/ComponentSelect";
import ComponentTable from "../../common/Table/ComponentTable";
import Th from "../../common/Table/Th";
import Tr from "../../common/Table/Tr";
import Td from "../../common/Table/Td";


class ImportView extends Component {

    constructor(props) {
        super(props);
        this.props.fetch_table_names();
    }

    render() {
        if (this.props.file === undefined){
            return <div></div>;
        }

        if (this.props.isLoading) return <ComponentLoading/>;

        return (
            <div>
                <ComponentCard label={'Map columns'} loading={this.props.isUploading}>
                    <ComponentAlert type={(this.props.error) ? 'danger' : 'success'} msg={this.props.msg}/>
                    <ComponentRow>
                        <ComponentSelect value={this.props.selectedTable} label={'Select Table'} options={this.props.tableNames} required={true} onChange={(e) =>this.props.onSelectTable('selectedTable', e.target.value)}/>
                    </ComponentRow>

                    <ComponentRow>
                        <ComponentTable style={{overflowX : 'auto', overflowY : 'scroll', whiteSpace : 'nowrap'}}>
                            <Tr>
                                <Th>Column Name</Th>
                                <Th>Type</Th>
                                <Th>CSV MAP</Th>
                                <Th>Datatype</Th>
                            </Tr>
                            {
                                this.props.tableColumnNames.map((item, index)=>{
                                    return(
                                        <Tr key={'item' + index}>
                                            <Td>{item.Field}</Td>
                                            <Td>{item.Type}</Td>
                                            <Td><ComponentSelect className={"col"} value={item.map_value} options={this.props.csvColumnNames} onChange={(e) =>this.props.onSelectMap(index, e.target.value)}/></Td>
                                            <Td><ComponentSelect className={"col"} value={item.datatype} options={this.props.datatypes} onChange={(e) =>this.props.onSelectDatatype(index, e.target.value)}/></Td>
                                        </Tr>
                                    )
                                })
                            }
                        </ComponentTable>
                    </ComponentRow>

                    <ComponentRow>
                        <button className="col-md-2 btn-primary btn" onClick={()=>this.props.onUpload()}>Upload</button>
                    </ComponentRow>
                </ComponentCard>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    const s = state.importReducer;

    return {
        file : state.uploadReducer.file,
        isLoading : s.isLoading,
        tableNames : s.tableNames,

        csvColumnNames : s.csvColumnNames,
        datatypes : s.datatypes,
        tableColumnNames : s.tableColumnNames,

        selectedTable : s.selectedTable,
        error : s.error,
        msg : s.msg,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        onSelectTable : (name, value) => onSelectTable(name, value),
        onSelectMap : (index, value) => onSelectMap(index, value),
        onSelectDatatype : (index, value) => onSelectDatatype(index, value),
        fetch_table_names : () => fetch_table_names(),
        onUpload : () => onUpload(),
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(ImportView);

