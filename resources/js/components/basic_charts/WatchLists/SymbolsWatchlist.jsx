import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import {download_fno_symbols, on_filter} from "../actions/symbolWatchListAction";
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
            fontWeight : 'bold',
        };

        let listStyleSelected = {
            border: '2px solid #2196f3',
            paddingTop : 5,
            paddingLeft : 3,
            fontWeight : 'bold',
        };


        return (
            <div>
                <div>
                    <ComponentInput value={this.props.queryStr} label={"Filter"} className="col" onChange={(e)=>this.props.on_filter('queryStr', e.target.value)}/>
                </div>

                <div style={{overflowY : 'scroll', height: 450, backgroundColor: 'white', marginTop : 5}}>
                    <ul style={{listStyle : 'none', paddingLeft : 0}}>
                        {
                            this.props.symbols.map((item, index)=>{
                                let listStyle = (item.symbol === this.props.selectedSymbol) ? listStyleSelected : listStyleNormal;
                                return <li key={'tx' + index} style={listStyle} onClick={()=> this.props.onSymbolClicked(item.symbol)}>{item.symbol}</li>
                            })
                        }
                    </ul>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    const s = state.symbolWatchListReducer;
    return {
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
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(SymbolsWatchlist);

