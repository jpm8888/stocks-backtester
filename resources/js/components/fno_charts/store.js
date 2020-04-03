import {applyMiddleware, createStore} from "redux";
import rootReducer from './reducers'
import thunk from 'redux-thunk';

const initial_state = {

};

const store = createStore(rootReducer, initial_state, applyMiddleware(thunk));

export default store;
