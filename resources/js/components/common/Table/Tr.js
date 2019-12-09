import React, {Component} from 'react';

class Tr extends Component {
    render() {
        return (
            <tr>
                {this.props.children}
            </tr>
        );
    }
}

export default Tr;