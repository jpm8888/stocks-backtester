import {combineReducers} from 'redux';
import indexReducer from "./indexReducer";
import filterReducer from "./filterReducer";


export default combineReducers({
    index_view : indexReducer,
    filterReducer : filterReducer,
})
