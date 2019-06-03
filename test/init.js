
import { initGame as d, Pep} from "./test.js"
Pep(11);


var obj = {
	
	alpha : 'Yanny',
	betha: 'Thor',
	gamma: 'oops'
	
};


var {betha, alpha} = obj;

console.log(alpha);
class Device {
    constructor ( params = {} ) {
        ( {
            status: this._status = 'off',
            brand: this._brand = 'ACME',
            firmware: this._firmware = 'unknown'
        } = params );
    }
}


var M = new Device({brand:'Motorola'});

console.log(M._status);
