import React, {Component} from 'react';

class ComponentRow extends Component {
    render() {
        return (
            <div className="row">
                {this.props.children}
            </div>
        );
    }
}

export default ComponentRow;
