import {
    ON_ERROR,
    ON_SUCCESS,
    SET_IS_LOADING,
    SET_MAP_VALUES,
    SET_TABLE_COLUMN_NAMES,
    SET_TABLE_NAMES
} from "../actions/importActions";

const initialState = {
    isLoading: false,
    tableNames : [],
    csvColumnNames : [],
    tableColumnNames : [],
    datatypes : [
            {id: 'text', name : 'text'},
            {id: 'number', name : 'number'},
            {id: 'decimal', name : 'decimal'},
            {id: 'Y-m-d', name : 'Y-m-d'},
            {id: 'd-m-Y', name : 'd-m-Y'},
            {id: 'date', name : 'date'},
        ],
    selectedTable : '',
    error : false,
    msg : '',

    errorLogs : [],
    importedRecords : 0,
};


export default function (state = initialState, action) {
    switch (action.type) {
        case SET_IS_LOADING :
            return {
                ...state,
                isLoading : action.payload
            };
        case SET_TABLE_NAMES :
            return {
                ...state,
                tableNames : action.payload
            };

        case SET_TABLE_COLUMN_NAMES :
            return {
                ...state,
                selectedTable : action.payload.value,
                tableColumnNames : action.payload.data,
            };

        case SET_MAP_VALUES :
            return {
                ...state,
                csvColumnNames : action.payload,
            };

        case ON_ERROR :
            return {
                ...state,
                error : action.payload.error,
                msg : action.payload.msg,
            };
        case ON_SUCCESS :
            return {
                ...state,
                csvColumnNames : [],
                tableColumnNames : [],
                selectedTable : '',
                error : false,
                msg : '',
                errorLogs : action.payload.errors,
                importedRecords : action.payload.imported_records,
            };

        default : return state;
    }
}
