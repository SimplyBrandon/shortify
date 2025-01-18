export default new class Events
{
    constructor()
    {
        this.events = {};
    }

    on(event, callback)
    {
        if(!this.events[event]) {
            this.events[event] = [];
        }

        this.events[event].push(callback);
    }

    off(event)
    {
        delete this.events[event];
    }

    trigger(event, data = null)
    {
        if(this.events[event]) {
            this.events[event].forEach(callback => callback(data));
        }
    }
}
