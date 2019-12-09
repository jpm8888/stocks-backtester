import React, {Component} from 'react';

class ComponentCard extends Component {
    render() {
        return (
            <div className="card" style={{width : '100%', margin : 10}}>
                <div className="card-body">
                    {this.props.children}
                </div>
            </div>
        );
    }
}

export default ComponentCard;