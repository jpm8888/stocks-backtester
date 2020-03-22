import {combineReducers} from 'redux';
import uploadReducer from "./uploadReducer";
import importReducer from "./importReducer";


export default combineReducers({
    uploadReducer: uploadReducer,
    importReducer: importReducer,
})
