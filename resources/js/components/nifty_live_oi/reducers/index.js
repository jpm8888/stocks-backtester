import {combineReducers} from 'redux';
import indexReducer from "./indexReducer";
import optionsReducer from "./optionsReducer";

export default combineReducers({
    index_view : indexReducer,
    options_view : optionsReducer,
})
