
import {service} from './service.js';
import {restartBadge} from './restartBadge.js';
import {twoStateButton} from './twoStateButton.js';
import {exitButton} from './../../onh/components/buttons/exitButton.js';

// Services class
export class services {
    
    /**
     * Services constructor
     * 
     * @param {string} sONH ONH service identifier
     * @param {string} srONH ONH reastart badge identifier
     * @param {string} sApache Apache service identifier
     * @param {string} sMySql MySQL service identifier
     * @param {string} btnAutoload Autoload button identifier
     * @param {string} btnONH ONH Start/Kill button identifier
     * @param {string} btnExitONH ONH exit button identifier
     */
    constructor(sONH, srONH, sApache, sMySql, btnAutoload, btnONH, btnExitONH) {
        
        // Service ONH
        this._serviceONH = new service(sONH);
        
        // Restart badge
        this._restartBadge = new restartBadge(srONH);
        
        // Service Apache
        this._serviceApache = new service(sApache);
        
        // Service MySQL
        this._serviceMySQL = new service(sMySql);
        
        // Autoload service button
        this._bAutoload = new twoStateButton(
                                btnAutoload,
                                'Disabled',
                                'Enabled',
                                '/services/autoload/',
                                true,
                                'btn btn-danger',
                                'btn btn-success'
        );
        
        // Start/Kill ONH button
        this._bONH = new twoStateButton(btnONH, 'Start service', 'Kill service', '/services/onh/');
        
        // Exit ONH button
        this._exitONH = new exitButton(btnExitONH, false);
        
        // Update service path
        this._updatePath = '/services/status';
    }
    
    /**
     * Get ONH service active state
     * 
     * @returns {bool}
     */
    isONHActive() {
        
        let ret = false;
        
        if (this._serviceONH.isActive() && !this._bONH.isLocked() && !this._exitONH.isLocked()) {
            ret = true;
        }
        
        return ret;
    }
    
    /**
     * Update services status
     * 
     * @returns {Promise}
     */
    async update() {
        
        try {
            // Get services status
            let res = await fetch(this._updatePath, { method: 'get' });
            let data = await res.json();

            if (data.error.state === false) {
                
                // Update services
                this._serviceONH.setState(data.services.openNetworkHMI);
                this._restartBadge.setState(data.restart);
                this._serviceApache.setState(data.services.Apache2);
                this._serviceMySQL.setState(data.services.MySQL);
                
                // Update autoload button
                this._bAutoload.setState(data.services.Autoload);
                // Update ONH button
                this._bONH.setState(this.isONHActive());
                
                // Show/Hide exit button
                if (this.isONHActive()) {
                    this._exitONH.show();
                    this._exitONH.enable();
                } else {
                    this._exitONH.hide();
                }
                
            } else {
                throw new Error(data.error.msg);
            }
            
            return Promise.resolve(true);

        } catch (err) {
            return Promise.reject(err);
        }
    }
}
