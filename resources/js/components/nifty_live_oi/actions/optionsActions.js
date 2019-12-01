import {FETCH_PARAMS} from "./types";


export const fetch_params = () => (dispatch) =>{
    // dispatch({type : FETCH, payload : {},});
    fetch('/nifty_live_oi/fetch')
        .then((res) => res.json()
            .then((res)=>{
                console.log(res);
                dispatch({
                    type : FETCH_PARAMS,
                    payload : res,
                });
                dispatch({type : FETCH_PARAMS, payload : res});
            }));
};
