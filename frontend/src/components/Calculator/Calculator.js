import React, {Component, Fragment} from 'react';
import axios from "axios";
import Log from "./Log";
import Panel from "./Panel";
import Alert from "./Alert";

class Calculator extends Component {
    state = {
        calculation: [],
        focus: 0,
        alert: false,
        calc_total: null,
        logs: []
    }

    addToCalculation = (new_state) => {
        if(!new_state) return new_state;
        if(new_state.focus > 2) return false; //Do not allow more than two digits and one operator
        
        this.setState({calculation: new_state.calculation, focus: new_state.focus})
    }
    
    alert = (message, type) => {
        this.setState({
            alert: {
                type,
                message
            }
        });
    }

    backspace = () => {
        const temp_state = {...this.state}

        if(temp_state.calculation[temp_state.focus] === undefined) return false;
        
        temp_state.calculation[temp_state.focus] = temp_state.calculation[temp_state.focus].slice(0, -1);
        
        if(temp_state.calculation[temp_state.focus] === undefined
        || temp_state.calculation[temp_state.focus] === "") {
            // Delete if the operator is empty to avoid any bugs.
            delete(temp_state.calculation[temp_state.focus]);
            if(temp_state.focus > 0) temp_state.focus = temp_state.focus - 1;
        }

        this.setState({
           calculation: temp_state.calculation,
           focus: temp_state.focus,
        });
    }

    calculate = async () => {
        const temp_state = {...this.state};
        // 1. validate if the state has all the elements
        if(temp_state.calculation.length < 3) {
            this.alert('You must input all the numbers and operator before sending this calculation', 'danger');
            return false;
        }

        // 2. Send to the server the calculation
        try {
            this.calcTotal(temp_state.calculation);

            const post_data = {"calculation": temp_state.calculation}
            const result =  await axios(
                'post', {
                    method: "post",
                    url: `${process.env.REACT_APP_API_ENDPOINT}/calculus`, 
                    data: post_data,
                    headers: {
                        "Accept": process.env.REACT_APP_API_ACCEPT_HEADER
                    }
                });
            
            this.reset();
            this.alert('Calculation saved with success', 'success');
            this.getLogs();
        } catch (error) {
            // If return "error", show the error message.
            const alert = {
                "type": "danger",
                "message": `"${error}" ocurred when trying to save your calculation`
            }

            if(error.response !== undefined && error.response.data !== undefined) {
                alert.message = `<strong>${error.response.data.error.title}:</strong> ${error.response.data.error.detail}`;
            }
            
            this.alert(alert.message, alert.type);
        }
    }


    calcTotal = (calc) => {
        try {
            const calc_total = eval(calc.join(''));
            this.setState({calc_total})
        } catch(error) {
            console.log(error);
        }
    }

    dismissAlert = () => {
        this.setState({alert: false})
    }

    getLogs = async () => {
        try {
            const result =  await axios({
                method: "get",
                url:`${process.env.REACT_APP_API_ENDPOINT}/calculus`,
                headers: {
                    "Accept": process.env.REACT_APP_API_ACCEPT_HEADER
                }
            });
            
            this.setState({
                logs: result.data.data.reverse()
            })
        } catch (error) {
            // If return "error", show the error message.
            const alert = {
                "type": "danger",
                "message": `"${error}" ocurred when trying to save your calculation`
            }

            if(error.response !== undefined) {
                alert.message = `<strong>${error.response.data.error.title}:</strong> ${error.response.data.error.detail}`;
            }
            
            this.alert(alert.message, alert.type);
        }
    }

    reset = () => {
        this.setState({
            calculation: [],
            focus: 0,
            calc_total: null,
        });
    }

    render() {
        return (
            <Fragment>
                <Alert 
                    alert={this.state.alert}
                    dismissAlert={this.dismissAlert}
                />
                <div className="row">
                    <div className="col-5 offset-md-3">
                        <Log
                            logs={this.state.logs} 
                            getLogs={this.getLogs} 
                        />
                        <Panel 
                            changeFocus={this.changeFocus}
                            state={this.state}
                            addToCalculation={this.addToCalculation}
                            calculate={this.calculate}
                            reset={this.reset}
                            backspace={this.backspace}
                        />
                    </div>
                </div>
            </Fragment>
        );
    }
}

export default Calculator;