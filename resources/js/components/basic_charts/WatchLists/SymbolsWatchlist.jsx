import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import {download_fno_symbols, on_filter, onToggleFavorite} from "../actions/symbolWatchListAction";
import ComponentInput from "../../common/ComponentInput";
import {onSymbolClicked} from "../actions/indexActions";

class SymbolsWatchlist extends Component {
    componentDidMount() {
        this.props.download_fno_symbols();
    }

    render() {
        let listStyleNormal = {
            borderBottom: '1px solid #f0f3fa',
            paddingTop : 5,
            paddingLeft : 3,
            borderBottomLeftRadius : 5,
            borderBottomRightRadius : 5,
        };

        let listStyleSelected = {
            border: '2px solid #2196f3',
            paddingTop : 5,
            paddingLeft : 3,
            borderRadius : 5,
        };

        let likeButtonStyle = {
            float : 'right',
            backgroundColor : 'transparent',
            border: 'none',
            verticalAlign : 'middle'
        };


        return (
            <div className="col-md-2">
                <div>
                    <ComponentInput value={this.props.queryStr} label={"Filter"} className="col" onChange={(e)=>this.props.on_filter('queryStr', e.target.value)}/>
                </div>

                <div style={{overflowY : 'scroll', height: 450, backgroundColor: 'white', marginTop : 5, borderRadius : 5}}>
                    <ul style={{listStyle : 'none', paddingLeft : 0}}>
                        {
                            this.props.symbols.map((item, index)=>{
                                let listStyle = (item.symbol === this.props.selectedSymbol) ? listStyleSelected : listStyleNormal;
                                let heartStyle = (item.fav_id > 0) ? 'fas fa-heart' : 'far fa-heart';
                                let loading = (this.props.loading && this.props.idx === index) ? "fas fa-yin-yang fa-spin" : heartStyle;

                                return (
                                    <div>
                                        <button style={likeButtonStyle}><i className={loading} style={{color: '#E91E63'}} onClick={()=>this.props.onToggleFavorite(index, item.symbol)}/></button>
                                        <li key={'tx' + index} style={listStyle} onClick={()=> this.props.onSymbolClicked(item.symbol)}>{item.symbol}</li>
                                    </div>
                                );
                            })
                        }
                    </ul>
                </div>

                <ul className="pagination" style={{marginTop : 10}}>
                    <li className="page-item active"><a className="page-link">🍇</a></li>
                    <li className="page-item"><a className="page-link">🍒</a></li>
                    <li className="page-item"><a className="page-link">❤️</a></li>
                </ul>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    const s = state.symbolWatchListReducer;
    return {
        idx: s.idx,
        loading: s.loading,

        symbols: s.filteredSymbols,
        queryStr: s.queryStr,
        selectedSymbol : state.indexReducer.selectedSymbol,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        download_fno_symbols : () => download_fno_symbols(),
        on_filter : (name, value) => on_filter(name, value),
        onSymbolClicked : (symbol) => onSymbolClicked(symbol),
        onToggleFavorite : (idx, symbol) => onToggleFavorite(idx, symbol),
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(SymbolsWatchlist);

