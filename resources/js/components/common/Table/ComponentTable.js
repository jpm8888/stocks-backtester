import React, {Component} from 'react';

class ComponentTable extends Component {

    render() {
        return (
            <div>
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