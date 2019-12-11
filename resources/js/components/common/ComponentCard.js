import React, {Component} from 'react';

class ComponentCard extends Component {


    render() {
        const label = (this.props.label) ? this.props.label : "";
        const loading = (this.props.loading);

        const LoadingView = () =>{
            if (loading){
                return <div className="spinner-border float-right  spinner-border-sm"></div>;
            }
            return <div></div>
        }

        return (
            <div className="col" style={{marginTop : 10}}>
                <div className="card">
                    <div className="card-header">
                        {label}
                        <LoadingView/>
                    </div>
                    <div className="card-body">
                        {this.props.children}
                    </div>
                </div>
            </div>
        );
    }
}

export default ComponentCard;
