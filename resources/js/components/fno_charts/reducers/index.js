import {combineReducers} from 'redux';
import indexReducer from "./indexReducer";
import symbolWatchListReducer from "./symbolWatchListReducer";


export default combineReducers({
    indexReducer: indexReducer,
    symbolWatchListReducer: symbolWatchListReducer,
})
