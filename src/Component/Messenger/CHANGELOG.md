# CHANGELOG for pre-release v0.*
This changelog contains important changes done for this component.

## v0.3.0 (2019-11-09)
Introducing the new Messenger component refactored from old MessageBus component.

##### Features

* Generic message bus for message dispatching and handling by middleware
* Generic middleware for payload validation
* Service adaption (CQRS + Event) of the message bus that allows dispatch commands, queries and events
* Transportation (sender and receiver) of messages across servers (message queues)