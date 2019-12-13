import {FETCH_PARAMS, ON_CHANGE_BNF_SELECT, SET_LOADING} from "./types";


export const fetch_params = () => (dispatch) =>{
    fetch('/bhavcopy_analyse/fetch_params')
        .then((res) => res.json()
            .then((res)=>{
                dispatch({
                    type : FETCH_PARAMS,
                    payload : res,
                });
                dispatch({type : FETCH_PARAMS, payload : res});
            }));
};


export const on_change_bnf_select = (selected) => (dispatch) =>{
    dispatch({type : SET_LOADING, payload : true});
    const symbol = selected.label;
    fetch('/bhavcopy_analyse/analyse/' + symbol)
        .then((res) => res.json()
            .then((res)=>{
                dispatch({
                    type : ON_CHANGE_BNF_SELECT,
                    payload : {
                        res,
                        selected
                    },
                });
                dispatch({type : SET_LOADING, payload : false});
            }));
};
