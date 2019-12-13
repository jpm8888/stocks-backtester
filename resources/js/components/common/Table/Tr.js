import React, {Component} from 'react';

class Tr extends Component {

    constructor(props){
        super(props);
    }

    render() {
        return (
            <tr {...this.props}>
                {this.props.children}
            </tr>
        );
    }
}

export default Tr;
