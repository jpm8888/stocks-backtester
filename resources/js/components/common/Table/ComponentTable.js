import React, {Component} from 'react';

class ComponentTable extends Component {

    render() {
        return (
            <div className="col-md-12" style={{overflowX : 'auto', overflowY : 'scroll', fontSize : 11, whiteSpace : 'nowrap'}}>
                <table className="table table-bordered table-sm">
                    <tbody>
                    {this.props.children}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default ComponentTable;
