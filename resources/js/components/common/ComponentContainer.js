import React, {Component} from 'react';

class ComponentContainer extends Component {
    render() {
        return (
            <div className="container">
                {this.props.children}
            </div>
        );
    }
}

export default ComponentContainer;
