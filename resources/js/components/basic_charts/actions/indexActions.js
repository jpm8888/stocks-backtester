import {ON_CHANGE, ON_CHART_CREATED} from "./types";
import store from '../store'

export const on_change = (name, value) => (dispatch) => {
    dispatch({
        type: ON_CHANGE,
        payload: {name: name, value: value}
    });
};

export const setWidget = (widget) => (dispatch) => {
    dispatch({
        type: ON_CHART_CREATED,
        payload: {
            widget : widget,
        }
    });
};


export const onSymbolClicked = (symbol) => (dispatch) => {
    let reducer = store.getState().indexReducer;

    let widget = reducer.widget;

    if (widget){
        let chart = widget.activeChart();
        chart.setSymbol(symbol, ()=>{
            //success callback
        })
    }
};
