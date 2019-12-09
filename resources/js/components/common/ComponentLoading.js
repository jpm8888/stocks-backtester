import React, {Component} from 'react';

class ComponentLoading extends Component {
    render() {
        return (
            <div>
                <div className="spinner-border text-dark" style={{display : 'block', margin: 'auto'}}></div>
            </div>
        );
    }
}

export default ComponentLoading;