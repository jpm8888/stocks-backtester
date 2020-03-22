import axios from "axios";
import store from '../store';
import {ON_RESET_FILE} from "./uploadActions";

export const MODULE_KEY = "module_import_";
export const SET_IS_LOADING = MODULE_KEY + 'set_is_loading';
export const SET_TABLE_NAMES = MODULE_KEY + 'set_table_names';
export const SET_TABLE_COLUMN_NAMES = MODULE_KEY + 'set_table_column_name';
export const SET_MAP_VALUES = MODULE_KEY + 'set_map_values';
export const ON_ERROR = MODULE_KEY + 'on_error';
export const ON_SUCCESS = MODULE_KEY + 'on_success';

export const fetch_table_names = () => (dispatch) => {
    axios.get('/utils/tables')
        .then((response) => {
            const data = response.data;
            dispatch({
                type: SET_TABLE_NAMES,
                payload: data,
            });
        });
};

export const onSelectTable = (name, value) => (dispatch) => {
    if (value === '') return;
    const file = store.getState().uploadReducer.file;
    if (!file) return;

    dispatch({type: SET_IS_LOADING, payload: true});
    axios.get('/utils/columns/' + value)
        .then((response) => {
            let data = response.data;

            data = data.map(item=>{
                item.map_value = '';
                item.datatype = '';
                return item;
            });

            dispatch({
                type: SET_TABLE_COLUMN_NAMES,
                payload: {
                    'value' : value,
                    'data' : data,
                },
            });

            fetch_map(file.id, dispatch)
        });
};


function fetch_map(file_id, dispatch) {
    axios.get('/utils/map/' + file_id)
        .then((response) => {
            const data = response.data;
            if (response.data.status === 1){
                dispatch({type: SET_MAP_VALUES, payload: data.csvColumnNames});
            }

            dispatch({type: SET_IS_LOADING, payload: false});
        });
};


export const onSelectMap = (index, value) => (dispatch) => {
    let tableColumnNames = store.getState().importReducer.tableColumnNames;
    let selectedTable = store.getState().importReducer.selectedTable;

    tableColumnNames = tableColumnNames.map((item, idx)=>{
        if (index === idx) {
            item.map_value = value;
        }
        return item;
    });
    dispatch({type: SET_TABLE_COLUMN_NAMES, payload: {value : selectedTable, data : tableColumnNames}});
};

export const onSelectDatatype = (index, value) => (dispatch) => {
    let tableColumnNames = store.getState().importReducer.tableColumnNames;
    let selectedTable = store.getState().importReducer.selectedTable;

    tableColumnNames = tableColumnNames.map((item, idx)=>{
        if (index === idx) {
            item.datatype = value;
        }
        return item;
    });
    dispatch({type: SET_TABLE_COLUMN_NAMES, payload: {value : selectedTable, data : tableColumnNames}});
};

export const onUpload = () => (dispatch) => {
    let tableColumnNames = store.getState().importReducer.tableColumnNames;
    let selectedTable = store.getState().importReducer.selectedTable;
    let fileId = store.getState().uploadReducer.file.id;

    let columnMap = [];

    let hasCreatedAt = false;
    let hasUpdateAt = false;

    tableColumnNames.map((item)=>{
        if (item.map_value && item.map_value !== '') {
            columnMap.push({column_name : item.Field, map_name : item.map_value, datatype : item.datatype});
        }
        if (item.Field === 'created_at') hasCreatedAt = true;
        if (item.Field === 'updated_at') hasUpdateAt = true;
    });

    let hasTimeStamp = (hasCreatedAt && hasUpdateAt);

    if (columnMap.length === 0) return;
    dispatch({type: SET_IS_LOADING, payload: true});
    axios.post('/utils/import', {tableName : selectedTable, columnMap : columnMap, fileId : fileId, hasTimeStamp : hasTimeStamp})
        .then((response) => {
            const data = response.data;
            dispatch({type: SET_IS_LOADING, payload: false});
            if (data.status === 1){
                dispatch({type: ON_RESET_FILE, payload: {}});
                dispatch({type: ON_SUCCESS, payload: response.data});
                alert('successfully imported...');
            }else if(data.status === 0){
                dispatch({type: ON_ERROR, payload: {error : true, msg : data.msg}});
            }
        });
};
