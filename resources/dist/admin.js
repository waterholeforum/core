/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@hotwired/stimulus/dist/stimulus.js":
/*!**********************************************************!*\
  !*** ./node_modules/@hotwired/stimulus/dist/stimulus.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Application": () => (/* binding */ Application),
/* harmony export */   "AttributeObserver": () => (/* binding */ AttributeObserver),
/* harmony export */   "Context": () => (/* binding */ Context),
/* harmony export */   "Controller": () => (/* binding */ Controller),
/* harmony export */   "ElementObserver": () => (/* binding */ ElementObserver),
/* harmony export */   "IndexedMultimap": () => (/* binding */ IndexedMultimap),
/* harmony export */   "Multimap": () => (/* binding */ Multimap),
/* harmony export */   "StringMapObserver": () => (/* binding */ StringMapObserver),
/* harmony export */   "TokenListObserver": () => (/* binding */ TokenListObserver),
/* harmony export */   "ValueListObserver": () => (/* binding */ ValueListObserver),
/* harmony export */   "add": () => (/* binding */ add),
/* harmony export */   "defaultSchema": () => (/* binding */ defaultSchema),
/* harmony export */   "del": () => (/* binding */ del),
/* harmony export */   "fetch": () => (/* binding */ fetch),
/* harmony export */   "prune": () => (/* binding */ prune)
/* harmony export */ });
/*
Stimulus 3.0.1
Copyright © 2021 Basecamp, LLC
 */
class EventListener {
    constructor(eventTarget, eventName, eventOptions) {
        this.eventTarget = eventTarget;
        this.eventName = eventName;
        this.eventOptions = eventOptions;
        this.unorderedBindings = new Set();
    }
    connect() {
        this.eventTarget.addEventListener(this.eventName, this, this.eventOptions);
    }
    disconnect() {
        this.eventTarget.removeEventListener(this.eventName, this, this.eventOptions);
    }
    bindingConnected(binding) {
        this.unorderedBindings.add(binding);
    }
    bindingDisconnected(binding) {
        this.unorderedBindings.delete(binding);
    }
    handleEvent(event) {
        const extendedEvent = extendEvent(event);
        for (const binding of this.bindings) {
            if (extendedEvent.immediatePropagationStopped) {
                break;
            }
            else {
                binding.handleEvent(extendedEvent);
            }
        }
    }
    get bindings() {
        return Array.from(this.unorderedBindings).sort((left, right) => {
            const leftIndex = left.index, rightIndex = right.index;
            return leftIndex < rightIndex ? -1 : leftIndex > rightIndex ? 1 : 0;
        });
    }
}
function extendEvent(event) {
    if ("immediatePropagationStopped" in event) {
        return event;
    }
    else {
        const { stopImmediatePropagation } = event;
        return Object.assign(event, {
            immediatePropagationStopped: false,
            stopImmediatePropagation() {
                this.immediatePropagationStopped = true;
                stopImmediatePropagation.call(this);
            }
        });
    }
}

class Dispatcher {
    constructor(application) {
        this.application = application;
        this.eventListenerMaps = new Map;
        this.started = false;
    }
    start() {
        if (!this.started) {
            this.started = true;
            this.eventListeners.forEach(eventListener => eventListener.connect());
        }
    }
    stop() {
        if (this.started) {
            this.started = false;
            this.eventListeners.forEach(eventListener => eventListener.disconnect());
        }
    }
    get eventListeners() {
        return Array.from(this.eventListenerMaps.values())
            .reduce((listeners, map) => listeners.concat(Array.from(map.values())), []);
    }
    bindingConnected(binding) {
        this.fetchEventListenerForBinding(binding).bindingConnected(binding);
    }
    bindingDisconnected(binding) {
        this.fetchEventListenerForBinding(binding).bindingDisconnected(binding);
    }
    handleError(error, message, detail = {}) {
        this.application.handleError(error, `Error ${message}`, detail);
    }
    fetchEventListenerForBinding(binding) {
        const { eventTarget, eventName, eventOptions } = binding;
        return this.fetchEventListener(eventTarget, eventName, eventOptions);
    }
    fetchEventListener(eventTarget, eventName, eventOptions) {
        const eventListenerMap = this.fetchEventListenerMapForEventTarget(eventTarget);
        const cacheKey = this.cacheKey(eventName, eventOptions);
        let eventListener = eventListenerMap.get(cacheKey);
        if (!eventListener) {
            eventListener = this.createEventListener(eventTarget, eventName, eventOptions);
            eventListenerMap.set(cacheKey, eventListener);
        }
        return eventListener;
    }
    createEventListener(eventTarget, eventName, eventOptions) {
        const eventListener = new EventListener(eventTarget, eventName, eventOptions);
        if (this.started) {
            eventListener.connect();
        }
        return eventListener;
    }
    fetchEventListenerMapForEventTarget(eventTarget) {
        let eventListenerMap = this.eventListenerMaps.get(eventTarget);
        if (!eventListenerMap) {
            eventListenerMap = new Map;
            this.eventListenerMaps.set(eventTarget, eventListenerMap);
        }
        return eventListenerMap;
    }
    cacheKey(eventName, eventOptions) {
        const parts = [eventName];
        Object.keys(eventOptions).sort().forEach(key => {
            parts.push(`${eventOptions[key] ? "" : "!"}${key}`);
        });
        return parts.join(":");
    }
}

const descriptorPattern = /^((.+?)(@(window|document))?->)?(.+?)(#([^:]+?))(:(.+))?$/;
function parseActionDescriptorString(descriptorString) {
    const source = descriptorString.trim();
    const matches = source.match(descriptorPattern) || [];
    return {
        eventTarget: parseEventTarget(matches[4]),
        eventName: matches[2],
        eventOptions: matches[9] ? parseEventOptions(matches[9]) : {},
        identifier: matches[5],
        methodName: matches[7]
    };
}
function parseEventTarget(eventTargetName) {
    if (eventTargetName == "window") {
        return window;
    }
    else if (eventTargetName == "document") {
        return document;
    }
}
function parseEventOptions(eventOptions) {
    return eventOptions.split(":").reduce((options, token) => Object.assign(options, { [token.replace(/^!/, "")]: !/^!/.test(token) }), {});
}
function stringifyEventTarget(eventTarget) {
    if (eventTarget == window) {
        return "window";
    }
    else if (eventTarget == document) {
        return "document";
    }
}

function camelize(value) {
    return value.replace(/(?:[_-])([a-z0-9])/g, (_, char) => char.toUpperCase());
}
function capitalize(value) {
    return value.charAt(0).toUpperCase() + value.slice(1);
}
function dasherize(value) {
    return value.replace(/([A-Z])/g, (_, char) => `-${char.toLowerCase()}`);
}
function tokenize(value) {
    return value.match(/[^\s]+/g) || [];
}

class Action {
    constructor(element, index, descriptor) {
        this.element = element;
        this.index = index;
        this.eventTarget = descriptor.eventTarget || element;
        this.eventName = descriptor.eventName || getDefaultEventNameForElement(element) || error("missing event name");
        this.eventOptions = descriptor.eventOptions || {};
        this.identifier = descriptor.identifier || error("missing identifier");
        this.methodName = descriptor.methodName || error("missing method name");
    }
    static forToken(token) {
        return new this(token.element, token.index, parseActionDescriptorString(token.content));
    }
    toString() {
        const eventNameSuffix = this.eventTargetName ? `@${this.eventTargetName}` : "";
        return `${this.eventName}${eventNameSuffix}->${this.identifier}#${this.methodName}`;
    }
    get params() {
        if (this.eventTarget instanceof Element) {
            return this.getParamsFromEventTargetAttributes(this.eventTarget);
        }
        else {
            return {};
        }
    }
    getParamsFromEventTargetAttributes(eventTarget) {
        const params = {};
        const pattern = new RegExp(`^data-${this.identifier}-(.+)-param$`);
        const attributes = Array.from(eventTarget.attributes);
        attributes.forEach(({ name, value }) => {
            const match = name.match(pattern);
            const key = match && match[1];
            if (key) {
                Object.assign(params, { [camelize(key)]: typecast(value) });
            }
        });
        return params;
    }
    get eventTargetName() {
        return stringifyEventTarget(this.eventTarget);
    }
}
const defaultEventNames = {
    "a": e => "click",
    "button": e => "click",
    "form": e => "submit",
    "details": e => "toggle",
    "input": e => e.getAttribute("type") == "submit" ? "click" : "input",
    "select": e => "change",
    "textarea": e => "input"
};
function getDefaultEventNameForElement(element) {
    const tagName = element.tagName.toLowerCase();
    if (tagName in defaultEventNames) {
        return defaultEventNames[tagName](element);
    }
}
function error(message) {
    throw new Error(message);
}
function typecast(value) {
    try {
        return JSON.parse(value);
    }
    catch (o_O) {
        return value;
    }
}

class Binding {
    constructor(context, action) {
        this.context = context;
        this.action = action;
    }
    get index() {
        return this.action.index;
    }
    get eventTarget() {
        return this.action.eventTarget;
    }
    get eventOptions() {
        return this.action.eventOptions;
    }
    get identifier() {
        return this.context.identifier;
    }
    handleEvent(event) {
        if (this.willBeInvokedByEvent(event)) {
            this.invokeWithEvent(event);
        }
    }
    get eventName() {
        return this.action.eventName;
    }
    get method() {
        const method = this.controller[this.methodName];
        if (typeof method == "function") {
            return method;
        }
        throw new Error(`Action "${this.action}" references undefined method "${this.methodName}"`);
    }
    invokeWithEvent(event) {
        const { target, currentTarget } = event;
        try {
            const { params } = this.action;
            const actionEvent = Object.assign(event, { params });
            this.method.call(this.controller, actionEvent);
            this.context.logDebugActivity(this.methodName, { event, target, currentTarget, action: this.methodName });
        }
        catch (error) {
            const { identifier, controller, element, index } = this;
            const detail = { identifier, controller, element, index, event };
            this.context.handleError(error, `invoking action "${this.action}"`, detail);
        }
    }
    willBeInvokedByEvent(event) {
        const eventTarget = event.target;
        if (this.element === eventTarget) {
            return true;
        }
        else if (eventTarget instanceof Element && this.element.contains(eventTarget)) {
            return this.scope.containsElement(eventTarget);
        }
        else {
            return this.scope.containsElement(this.action.element);
        }
    }
    get controller() {
        return this.context.controller;
    }
    get methodName() {
        return this.action.methodName;
    }
    get element() {
        return this.scope.element;
    }
    get scope() {
        return this.context.scope;
    }
}

class ElementObserver {
    constructor(element, delegate) {
        this.mutationObserverInit = { attributes: true, childList: true, subtree: true };
        this.element = element;
        this.started = false;
        this.delegate = delegate;
        this.elements = new Set;
        this.mutationObserver = new MutationObserver((mutations) => this.processMutations(mutations));
    }
    start() {
        if (!this.started) {
            this.started = true;
            this.mutationObserver.observe(this.element, this.mutationObserverInit);
            this.refresh();
        }
    }
    pause(callback) {
        if (this.started) {
            this.mutationObserver.disconnect();
            this.started = false;
        }
        callback();
        if (!this.started) {
            this.mutationObserver.observe(this.element, this.mutationObserverInit);
            this.started = true;
        }
    }
    stop() {
        if (this.started) {
            this.mutationObserver.takeRecords();
            this.mutationObserver.disconnect();
            this.started = false;
        }
    }
    refresh() {
        if (this.started) {
            const matches = new Set(this.matchElementsInTree());
            for (const element of Array.from(this.elements)) {
                if (!matches.has(element)) {
                    this.removeElement(element);
                }
            }
            for (const element of Array.from(matches)) {
                this.addElement(element);
            }
        }
    }
    processMutations(mutations) {
        if (this.started) {
            for (const mutation of mutations) {
                this.processMutation(mutation);
            }
        }
    }
    processMutation(mutation) {
        if (mutation.type == "attributes") {
            this.processAttributeChange(mutation.target, mutation.attributeName);
        }
        else if (mutation.type == "childList") {
            this.processRemovedNodes(mutation.removedNodes);
            this.processAddedNodes(mutation.addedNodes);
        }
    }
    processAttributeChange(node, attributeName) {
        const element = node;
        if (this.elements.has(element)) {
            if (this.delegate.elementAttributeChanged && this.matchElement(element)) {
                this.delegate.elementAttributeChanged(element, attributeName);
            }
            else {
                this.removeElement(element);
            }
        }
        else if (this.matchElement(element)) {
            this.addElement(element);
        }
    }
    processRemovedNodes(nodes) {
        for (const node of Array.from(nodes)) {
            const element = this.elementFromNode(node);
            if (element) {
                this.processTree(element, this.removeElement);
            }
        }
    }
    processAddedNodes(nodes) {
        for (const node of Array.from(nodes)) {
            const element = this.elementFromNode(node);
            if (element && this.elementIsActive(element)) {
                this.processTree(element, this.addElement);
            }
        }
    }
    matchElement(element) {
        return this.delegate.matchElement(element);
    }
    matchElementsInTree(tree = this.element) {
        return this.delegate.matchElementsInTree(tree);
    }
    processTree(tree, processor) {
        for (const element of this.matchElementsInTree(tree)) {
            processor.call(this, element);
        }
    }
    elementFromNode(node) {
        if (node.nodeType == Node.ELEMENT_NODE) {
            return node;
        }
    }
    elementIsActive(element) {
        if (element.isConnected != this.element.isConnected) {
            return false;
        }
        else {
            return this.element.contains(element);
        }
    }
    addElement(element) {
        if (!this.elements.has(element)) {
            if (this.elementIsActive(element)) {
                this.elements.add(element);
                if (this.delegate.elementMatched) {
                    this.delegate.elementMatched(element);
                }
            }
        }
    }
    removeElement(element) {
        if (this.elements.has(element)) {
            this.elements.delete(element);
            if (this.delegate.elementUnmatched) {
                this.delegate.elementUnmatched(element);
            }
        }
    }
}

class AttributeObserver {
    constructor(element, attributeName, delegate) {
        this.attributeName = attributeName;
        this.delegate = delegate;
        this.elementObserver = new ElementObserver(element, this);
    }
    get element() {
        return this.elementObserver.element;
    }
    get selector() {
        return `[${this.attributeName}]`;
    }
    start() {
        this.elementObserver.start();
    }
    pause(callback) {
        this.elementObserver.pause(callback);
    }
    stop() {
        this.elementObserver.stop();
    }
    refresh() {
        this.elementObserver.refresh();
    }
    get started() {
        return this.elementObserver.started;
    }
    matchElement(element) {
        return element.hasAttribute(this.attributeName);
    }
    matchElementsInTree(tree) {
        const match = this.matchElement(tree) ? [tree] : [];
        const matches = Array.from(tree.querySelectorAll(this.selector));
        return match.concat(matches);
    }
    elementMatched(element) {
        if (this.delegate.elementMatchedAttribute) {
            this.delegate.elementMatchedAttribute(element, this.attributeName);
        }
    }
    elementUnmatched(element) {
        if (this.delegate.elementUnmatchedAttribute) {
            this.delegate.elementUnmatchedAttribute(element, this.attributeName);
        }
    }
    elementAttributeChanged(element, attributeName) {
        if (this.delegate.elementAttributeValueChanged && this.attributeName == attributeName) {
            this.delegate.elementAttributeValueChanged(element, attributeName);
        }
    }
}

class StringMapObserver {
    constructor(element, delegate) {
        this.element = element;
        this.delegate = delegate;
        this.started = false;
        this.stringMap = new Map;
        this.mutationObserver = new MutationObserver(mutations => this.processMutations(mutations));
    }
    start() {
        if (!this.started) {
            this.started = true;
            this.mutationObserver.observe(this.element, { attributes: true, attributeOldValue: true });
            this.refresh();
        }
    }
    stop() {
        if (this.started) {
            this.mutationObserver.takeRecords();
            this.mutationObserver.disconnect();
            this.started = false;
        }
    }
    refresh() {
        if (this.started) {
            for (const attributeName of this.knownAttributeNames) {
                this.refreshAttribute(attributeName, null);
            }
        }
    }
    processMutations(mutations) {
        if (this.started) {
            for (const mutation of mutations) {
                this.processMutation(mutation);
            }
        }
    }
    processMutation(mutation) {
        const attributeName = mutation.attributeName;
        if (attributeName) {
            this.refreshAttribute(attributeName, mutation.oldValue);
        }
    }
    refreshAttribute(attributeName, oldValue) {
        const key = this.delegate.getStringMapKeyForAttribute(attributeName);
        if (key != null) {
            if (!this.stringMap.has(attributeName)) {
                this.stringMapKeyAdded(key, attributeName);
            }
            const value = this.element.getAttribute(attributeName);
            if (this.stringMap.get(attributeName) != value) {
                this.stringMapValueChanged(value, key, oldValue);
            }
            if (value == null) {
                const oldValue = this.stringMap.get(attributeName);
                this.stringMap.delete(attributeName);
                if (oldValue)
                    this.stringMapKeyRemoved(key, attributeName, oldValue);
            }
            else {
                this.stringMap.set(attributeName, value);
            }
        }
    }
    stringMapKeyAdded(key, attributeName) {
        if (this.delegate.stringMapKeyAdded) {
            this.delegate.stringMapKeyAdded(key, attributeName);
        }
    }
    stringMapValueChanged(value, key, oldValue) {
        if (this.delegate.stringMapValueChanged) {
            this.delegate.stringMapValueChanged(value, key, oldValue);
        }
    }
    stringMapKeyRemoved(key, attributeName, oldValue) {
        if (this.delegate.stringMapKeyRemoved) {
            this.delegate.stringMapKeyRemoved(key, attributeName, oldValue);
        }
    }
    get knownAttributeNames() {
        return Array.from(new Set(this.currentAttributeNames.concat(this.recordedAttributeNames)));
    }
    get currentAttributeNames() {
        return Array.from(this.element.attributes).map(attribute => attribute.name);
    }
    get recordedAttributeNames() {
        return Array.from(this.stringMap.keys());
    }
}

function add(map, key, value) {
    fetch(map, key).add(value);
}
function del(map, key, value) {
    fetch(map, key).delete(value);
    prune(map, key);
}
function fetch(map, key) {
    let values = map.get(key);
    if (!values) {
        values = new Set();
        map.set(key, values);
    }
    return values;
}
function prune(map, key) {
    const values = map.get(key);
    if (values != null && values.size == 0) {
        map.delete(key);
    }
}

class Multimap {
    constructor() {
        this.valuesByKey = new Map();
    }
    get keys() {
        return Array.from(this.valuesByKey.keys());
    }
    get values() {
        const sets = Array.from(this.valuesByKey.values());
        return sets.reduce((values, set) => values.concat(Array.from(set)), []);
    }
    get size() {
        const sets = Array.from(this.valuesByKey.values());
        return sets.reduce((size, set) => size + set.size, 0);
    }
    add(key, value) {
        add(this.valuesByKey, key, value);
    }
    delete(key, value) {
        del(this.valuesByKey, key, value);
    }
    has(key, value) {
        const values = this.valuesByKey.get(key);
        return values != null && values.has(value);
    }
    hasKey(key) {
        return this.valuesByKey.has(key);
    }
    hasValue(value) {
        const sets = Array.from(this.valuesByKey.values());
        return sets.some(set => set.has(value));
    }
    getValuesForKey(key) {
        const values = this.valuesByKey.get(key);
        return values ? Array.from(values) : [];
    }
    getKeysForValue(value) {
        return Array.from(this.valuesByKey)
            .filter(([key, values]) => values.has(value))
            .map(([key, values]) => key);
    }
}

class IndexedMultimap extends Multimap {
    constructor() {
        super();
        this.keysByValue = new Map;
    }
    get values() {
        return Array.from(this.keysByValue.keys());
    }
    add(key, value) {
        super.add(key, value);
        add(this.keysByValue, value, key);
    }
    delete(key, value) {
        super.delete(key, value);
        del(this.keysByValue, value, key);
    }
    hasValue(value) {
        return this.keysByValue.has(value);
    }
    getKeysForValue(value) {
        const set = this.keysByValue.get(value);
        return set ? Array.from(set) : [];
    }
}

class TokenListObserver {
    constructor(element, attributeName, delegate) {
        this.attributeObserver = new AttributeObserver(element, attributeName, this);
        this.delegate = delegate;
        this.tokensByElement = new Multimap;
    }
    get started() {
        return this.attributeObserver.started;
    }
    start() {
        this.attributeObserver.start();
    }
    pause(callback) {
        this.attributeObserver.pause(callback);
    }
    stop() {
        this.attributeObserver.stop();
    }
    refresh() {
        this.attributeObserver.refresh();
    }
    get element() {
        return this.attributeObserver.element;
    }
    get attributeName() {
        return this.attributeObserver.attributeName;
    }
    elementMatchedAttribute(element) {
        this.tokensMatched(this.readTokensForElement(element));
    }
    elementAttributeValueChanged(element) {
        const [unmatchedTokens, matchedTokens] = this.refreshTokensForElement(element);
        this.tokensUnmatched(unmatchedTokens);
        this.tokensMatched(matchedTokens);
    }
    elementUnmatchedAttribute(element) {
        this.tokensUnmatched(this.tokensByElement.getValuesForKey(element));
    }
    tokensMatched(tokens) {
        tokens.forEach(token => this.tokenMatched(token));
    }
    tokensUnmatched(tokens) {
        tokens.forEach(token => this.tokenUnmatched(token));
    }
    tokenMatched(token) {
        this.delegate.tokenMatched(token);
        this.tokensByElement.add(token.element, token);
    }
    tokenUnmatched(token) {
        this.delegate.tokenUnmatched(token);
        this.tokensByElement.delete(token.element, token);
    }
    refreshTokensForElement(element) {
        const previousTokens = this.tokensByElement.getValuesForKey(element);
        const currentTokens = this.readTokensForElement(element);
        const firstDifferingIndex = zip(previousTokens, currentTokens)
            .findIndex(([previousToken, currentToken]) => !tokensAreEqual(previousToken, currentToken));
        if (firstDifferingIndex == -1) {
            return [[], []];
        }
        else {
            return [previousTokens.slice(firstDifferingIndex), currentTokens.slice(firstDifferingIndex)];
        }
    }
    readTokensForElement(element) {
        const attributeName = this.attributeName;
        const tokenString = element.getAttribute(attributeName) || "";
        return parseTokenString(tokenString, element, attributeName);
    }
}
function parseTokenString(tokenString, element, attributeName) {
    return tokenString.trim().split(/\s+/).filter(content => content.length)
        .map((content, index) => ({ element, attributeName, content, index }));
}
function zip(left, right) {
    const length = Math.max(left.length, right.length);
    return Array.from({ length }, (_, index) => [left[index], right[index]]);
}
function tokensAreEqual(left, right) {
    return left && right && left.index == right.index && left.content == right.content;
}

class ValueListObserver {
    constructor(element, attributeName, delegate) {
        this.tokenListObserver = new TokenListObserver(element, attributeName, this);
        this.delegate = delegate;
        this.parseResultsByToken = new WeakMap;
        this.valuesByTokenByElement = new WeakMap;
    }
    get started() {
        return this.tokenListObserver.started;
    }
    start() {
        this.tokenListObserver.start();
    }
    stop() {
        this.tokenListObserver.stop();
    }
    refresh() {
        this.tokenListObserver.refresh();
    }
    get element() {
        return this.tokenListObserver.element;
    }
    get attributeName() {
        return this.tokenListObserver.attributeName;
    }
    tokenMatched(token) {
        const { element } = token;
        const { value } = this.fetchParseResultForToken(token);
        if (value) {
            this.fetchValuesByTokenForElement(element).set(token, value);
            this.delegate.elementMatchedValue(element, value);
        }
    }
    tokenUnmatched(token) {
        const { element } = token;
        const { value } = this.fetchParseResultForToken(token);
        if (value) {
            this.fetchValuesByTokenForElement(element).delete(token);
            this.delegate.elementUnmatchedValue(element, value);
        }
    }
    fetchParseResultForToken(token) {
        let parseResult = this.parseResultsByToken.get(token);
        if (!parseResult) {
            parseResult = this.parseToken(token);
            this.parseResultsByToken.set(token, parseResult);
        }
        return parseResult;
    }
    fetchValuesByTokenForElement(element) {
        let valuesByToken = this.valuesByTokenByElement.get(element);
        if (!valuesByToken) {
            valuesByToken = new Map;
            this.valuesByTokenByElement.set(element, valuesByToken);
        }
        return valuesByToken;
    }
    parseToken(token) {
        try {
            const value = this.delegate.parseValueForToken(token);
            return { value };
        }
        catch (error) {
            return { error };
        }
    }
}

class BindingObserver {
    constructor(context, delegate) {
        this.context = context;
        this.delegate = delegate;
        this.bindingsByAction = new Map;
    }
    start() {
        if (!this.valueListObserver) {
            this.valueListObserver = new ValueListObserver(this.element, this.actionAttribute, this);
            this.valueListObserver.start();
        }
    }
    stop() {
        if (this.valueListObserver) {
            this.valueListObserver.stop();
            delete this.valueListObserver;
            this.disconnectAllActions();
        }
    }
    get element() {
        return this.context.element;
    }
    get identifier() {
        return this.context.identifier;
    }
    get actionAttribute() {
        return this.schema.actionAttribute;
    }
    get schema() {
        return this.context.schema;
    }
    get bindings() {
        return Array.from(this.bindingsByAction.values());
    }
    connectAction(action) {
        const binding = new Binding(this.context, action);
        this.bindingsByAction.set(action, binding);
        this.delegate.bindingConnected(binding);
    }
    disconnectAction(action) {
        const binding = this.bindingsByAction.get(action);
        if (binding) {
            this.bindingsByAction.delete(action);
            this.delegate.bindingDisconnected(binding);
        }
    }
    disconnectAllActions() {
        this.bindings.forEach(binding => this.delegate.bindingDisconnected(binding));
        this.bindingsByAction.clear();
    }
    parseValueForToken(token) {
        const action = Action.forToken(token);
        if (action.identifier == this.identifier) {
            return action;
        }
    }
    elementMatchedValue(element, action) {
        this.connectAction(action);
    }
    elementUnmatchedValue(element, action) {
        this.disconnectAction(action);
    }
}

class ValueObserver {
    constructor(context, receiver) {
        this.context = context;
        this.receiver = receiver;
        this.stringMapObserver = new StringMapObserver(this.element, this);
        this.valueDescriptorMap = this.controller.valueDescriptorMap;
        this.invokeChangedCallbacksForDefaultValues();
    }
    start() {
        this.stringMapObserver.start();
    }
    stop() {
        this.stringMapObserver.stop();
    }
    get element() {
        return this.context.element;
    }
    get controller() {
        return this.context.controller;
    }
    getStringMapKeyForAttribute(attributeName) {
        if (attributeName in this.valueDescriptorMap) {
            return this.valueDescriptorMap[attributeName].name;
        }
    }
    stringMapKeyAdded(key, attributeName) {
        const descriptor = this.valueDescriptorMap[attributeName];
        if (!this.hasValue(key)) {
            this.invokeChangedCallback(key, descriptor.writer(this.receiver[key]), descriptor.writer(descriptor.defaultValue));
        }
    }
    stringMapValueChanged(value, name, oldValue) {
        const descriptor = this.valueDescriptorNameMap[name];
        if (value === null)
            return;
        if (oldValue === null) {
            oldValue = descriptor.writer(descriptor.defaultValue);
        }
        this.invokeChangedCallback(name, value, oldValue);
    }
    stringMapKeyRemoved(key, attributeName, oldValue) {
        const descriptor = this.valueDescriptorNameMap[key];
        if (this.hasValue(key)) {
            this.invokeChangedCallback(key, descriptor.writer(this.receiver[key]), oldValue);
        }
        else {
            this.invokeChangedCallback(key, descriptor.writer(descriptor.defaultValue), oldValue);
        }
    }
    invokeChangedCallbacksForDefaultValues() {
        for (const { key, name, defaultValue, writer } of this.valueDescriptors) {
            if (defaultValue != undefined && !this.controller.data.has(key)) {
                this.invokeChangedCallback(name, writer(defaultValue), undefined);
            }
        }
    }
    invokeChangedCallback(name, rawValue, rawOldValue) {
        const changedMethodName = `${name}Changed`;
        const changedMethod = this.receiver[changedMethodName];
        if (typeof changedMethod == "function") {
            const descriptor = this.valueDescriptorNameMap[name];
            const value = descriptor.reader(rawValue);
            let oldValue = rawOldValue;
            if (rawOldValue) {
                oldValue = descriptor.reader(rawOldValue);
            }
            changedMethod.call(this.receiver, value, oldValue);
        }
    }
    get valueDescriptors() {
        const { valueDescriptorMap } = this;
        return Object.keys(valueDescriptorMap).map(key => valueDescriptorMap[key]);
    }
    get valueDescriptorNameMap() {
        const descriptors = {};
        Object.keys(this.valueDescriptorMap).forEach(key => {
            const descriptor = this.valueDescriptorMap[key];
            descriptors[descriptor.name] = descriptor;
        });
        return descriptors;
    }
    hasValue(attributeName) {
        const descriptor = this.valueDescriptorNameMap[attributeName];
        const hasMethodName = `has${capitalize(descriptor.name)}`;
        return this.receiver[hasMethodName];
    }
}

class TargetObserver {
    constructor(context, delegate) {
        this.context = context;
        this.delegate = delegate;
        this.targetsByName = new Multimap;
    }
    start() {
        if (!this.tokenListObserver) {
            this.tokenListObserver = new TokenListObserver(this.element, this.attributeName, this);
            this.tokenListObserver.start();
        }
    }
    stop() {
        if (this.tokenListObserver) {
            this.disconnectAllTargets();
            this.tokenListObserver.stop();
            delete this.tokenListObserver;
        }
    }
    tokenMatched({ element, content: name }) {
        if (this.scope.containsElement(element)) {
            this.connectTarget(element, name);
        }
    }
    tokenUnmatched({ element, content: name }) {
        this.disconnectTarget(element, name);
    }
    connectTarget(element, name) {
        var _a;
        if (!this.targetsByName.has(name, element)) {
            this.targetsByName.add(name, element);
            (_a = this.tokenListObserver) === null || _a === void 0 ? void 0 : _a.pause(() => this.delegate.targetConnected(element, name));
        }
    }
    disconnectTarget(element, name) {
        var _a;
        if (this.targetsByName.has(name, element)) {
            this.targetsByName.delete(name, element);
            (_a = this.tokenListObserver) === null || _a === void 0 ? void 0 : _a.pause(() => this.delegate.targetDisconnected(element, name));
        }
    }
    disconnectAllTargets() {
        for (const name of this.targetsByName.keys) {
            for (const element of this.targetsByName.getValuesForKey(name)) {
                this.disconnectTarget(element, name);
            }
        }
    }
    get attributeName() {
        return `data-${this.context.identifier}-target`;
    }
    get element() {
        return this.context.element;
    }
    get scope() {
        return this.context.scope;
    }
}

class Context {
    constructor(module, scope) {
        this.logDebugActivity = (functionName, detail = {}) => {
            const { identifier, controller, element } = this;
            detail = Object.assign({ identifier, controller, element }, detail);
            this.application.logDebugActivity(this.identifier, functionName, detail);
        };
        this.module = module;
        this.scope = scope;
        this.controller = new module.controllerConstructor(this);
        this.bindingObserver = new BindingObserver(this, this.dispatcher);
        this.valueObserver = new ValueObserver(this, this.controller);
        this.targetObserver = new TargetObserver(this, this);
        try {
            this.controller.initialize();
            this.logDebugActivity("initialize");
        }
        catch (error) {
            this.handleError(error, "initializing controller");
        }
    }
    connect() {
        this.bindingObserver.start();
        this.valueObserver.start();
        this.targetObserver.start();
        try {
            this.controller.connect();
            this.logDebugActivity("connect");
        }
        catch (error) {
            this.handleError(error, "connecting controller");
        }
    }
    disconnect() {
        try {
            this.controller.disconnect();
            this.logDebugActivity("disconnect");
        }
        catch (error) {
            this.handleError(error, "disconnecting controller");
        }
        this.targetObserver.stop();
        this.valueObserver.stop();
        this.bindingObserver.stop();
    }
    get application() {
        return this.module.application;
    }
    get identifier() {
        return this.module.identifier;
    }
    get schema() {
        return this.application.schema;
    }
    get dispatcher() {
        return this.application.dispatcher;
    }
    get element() {
        return this.scope.element;
    }
    get parentElement() {
        return this.element.parentElement;
    }
    handleError(error, message, detail = {}) {
        const { identifier, controller, element } = this;
        detail = Object.assign({ identifier, controller, element }, detail);
        this.application.handleError(error, `Error ${message}`, detail);
    }
    targetConnected(element, name) {
        this.invokeControllerMethod(`${name}TargetConnected`, element);
    }
    targetDisconnected(element, name) {
        this.invokeControllerMethod(`${name}TargetDisconnected`, element);
    }
    invokeControllerMethod(methodName, ...args) {
        const controller = this.controller;
        if (typeof controller[methodName] == "function") {
            controller[methodName](...args);
        }
    }
}

function readInheritableStaticArrayValues(constructor, propertyName) {
    const ancestors = getAncestorsForConstructor(constructor);
    return Array.from(ancestors.reduce((values, constructor) => {
        getOwnStaticArrayValues(constructor, propertyName).forEach(name => values.add(name));
        return values;
    }, new Set));
}
function readInheritableStaticObjectPairs(constructor, propertyName) {
    const ancestors = getAncestorsForConstructor(constructor);
    return ancestors.reduce((pairs, constructor) => {
        pairs.push(...getOwnStaticObjectPairs(constructor, propertyName));
        return pairs;
    }, []);
}
function getAncestorsForConstructor(constructor) {
    const ancestors = [];
    while (constructor) {
        ancestors.push(constructor);
        constructor = Object.getPrototypeOf(constructor);
    }
    return ancestors.reverse();
}
function getOwnStaticArrayValues(constructor, propertyName) {
    const definition = constructor[propertyName];
    return Array.isArray(definition) ? definition : [];
}
function getOwnStaticObjectPairs(constructor, propertyName) {
    const definition = constructor[propertyName];
    return definition ? Object.keys(definition).map(key => [key, definition[key]]) : [];
}

function bless(constructor) {
    return shadow(constructor, getBlessedProperties(constructor));
}
function shadow(constructor, properties) {
    const shadowConstructor = extend(constructor);
    const shadowProperties = getShadowProperties(constructor.prototype, properties);
    Object.defineProperties(shadowConstructor.prototype, shadowProperties);
    return shadowConstructor;
}
function getBlessedProperties(constructor) {
    const blessings = readInheritableStaticArrayValues(constructor, "blessings");
    return blessings.reduce((blessedProperties, blessing) => {
        const properties = blessing(constructor);
        for (const key in properties) {
            const descriptor = blessedProperties[key] || {};
            blessedProperties[key] = Object.assign(descriptor, properties[key]);
        }
        return blessedProperties;
    }, {});
}
function getShadowProperties(prototype, properties) {
    return getOwnKeys(properties).reduce((shadowProperties, key) => {
        const descriptor = getShadowedDescriptor(prototype, properties, key);
        if (descriptor) {
            Object.assign(shadowProperties, { [key]: descriptor });
        }
        return shadowProperties;
    }, {});
}
function getShadowedDescriptor(prototype, properties, key) {
    const shadowingDescriptor = Object.getOwnPropertyDescriptor(prototype, key);
    const shadowedByValue = shadowingDescriptor && "value" in shadowingDescriptor;
    if (!shadowedByValue) {
        const descriptor = Object.getOwnPropertyDescriptor(properties, key).value;
        if (shadowingDescriptor) {
            descriptor.get = shadowingDescriptor.get || descriptor.get;
            descriptor.set = shadowingDescriptor.set || descriptor.set;
        }
        return descriptor;
    }
}
const getOwnKeys = (() => {
    if (typeof Object.getOwnPropertySymbols == "function") {
        return (object) => [
            ...Object.getOwnPropertyNames(object),
            ...Object.getOwnPropertySymbols(object)
        ];
    }
    else {
        return Object.getOwnPropertyNames;
    }
})();
const extend = (() => {
    function extendWithReflect(constructor) {
        function extended() {
            return Reflect.construct(constructor, arguments, new.target);
        }
        extended.prototype = Object.create(constructor.prototype, {
            constructor: { value: extended }
        });
        Reflect.setPrototypeOf(extended, constructor);
        return extended;
    }
    function testReflectExtension() {
        const a = function () { this.a.call(this); };
        const b = extendWithReflect(a);
        b.prototype.a = function () { };
        return new b;
    }
    try {
        testReflectExtension();
        return extendWithReflect;
    }
    catch (error) {
        return (constructor) => class extended extends constructor {
        };
    }
})();

function blessDefinition(definition) {
    return {
        identifier: definition.identifier,
        controllerConstructor: bless(definition.controllerConstructor)
    };
}

class Module {
    constructor(application, definition) {
        this.application = application;
        this.definition = blessDefinition(definition);
        this.contextsByScope = new WeakMap;
        this.connectedContexts = new Set;
    }
    get identifier() {
        return this.definition.identifier;
    }
    get controllerConstructor() {
        return this.definition.controllerConstructor;
    }
    get contexts() {
        return Array.from(this.connectedContexts);
    }
    connectContextForScope(scope) {
        const context = this.fetchContextForScope(scope);
        this.connectedContexts.add(context);
        context.connect();
    }
    disconnectContextForScope(scope) {
        const context = this.contextsByScope.get(scope);
        if (context) {
            this.connectedContexts.delete(context);
            context.disconnect();
        }
    }
    fetchContextForScope(scope) {
        let context = this.contextsByScope.get(scope);
        if (!context) {
            context = new Context(this, scope);
            this.contextsByScope.set(scope, context);
        }
        return context;
    }
}

class ClassMap {
    constructor(scope) {
        this.scope = scope;
    }
    has(name) {
        return this.data.has(this.getDataKey(name));
    }
    get(name) {
        return this.getAll(name)[0];
    }
    getAll(name) {
        const tokenString = this.data.get(this.getDataKey(name)) || "";
        return tokenize(tokenString);
    }
    getAttributeName(name) {
        return this.data.getAttributeNameForKey(this.getDataKey(name));
    }
    getDataKey(name) {
        return `${name}-class`;
    }
    get data() {
        return this.scope.data;
    }
}

class DataMap {
    constructor(scope) {
        this.scope = scope;
    }
    get element() {
        return this.scope.element;
    }
    get identifier() {
        return this.scope.identifier;
    }
    get(key) {
        const name = this.getAttributeNameForKey(key);
        return this.element.getAttribute(name);
    }
    set(key, value) {
        const name = this.getAttributeNameForKey(key);
        this.element.setAttribute(name, value);
        return this.get(key);
    }
    has(key) {
        const name = this.getAttributeNameForKey(key);
        return this.element.hasAttribute(name);
    }
    delete(key) {
        if (this.has(key)) {
            const name = this.getAttributeNameForKey(key);
            this.element.removeAttribute(name);
            return true;
        }
        else {
            return false;
        }
    }
    getAttributeNameForKey(key) {
        return `data-${this.identifier}-${dasherize(key)}`;
    }
}

class Guide {
    constructor(logger) {
        this.warnedKeysByObject = new WeakMap;
        this.logger = logger;
    }
    warn(object, key, message) {
        let warnedKeys = this.warnedKeysByObject.get(object);
        if (!warnedKeys) {
            warnedKeys = new Set;
            this.warnedKeysByObject.set(object, warnedKeys);
        }
        if (!warnedKeys.has(key)) {
            warnedKeys.add(key);
            this.logger.warn(message, object);
        }
    }
}

function attributeValueContainsToken(attributeName, token) {
    return `[${attributeName}~="${token}"]`;
}

class TargetSet {
    constructor(scope) {
        this.scope = scope;
    }
    get element() {
        return this.scope.element;
    }
    get identifier() {
        return this.scope.identifier;
    }
    get schema() {
        return this.scope.schema;
    }
    has(targetName) {
        return this.find(targetName) != null;
    }
    find(...targetNames) {
        return targetNames.reduce((target, targetName) => target
            || this.findTarget(targetName)
            || this.findLegacyTarget(targetName), undefined);
    }
    findAll(...targetNames) {
        return targetNames.reduce((targets, targetName) => [
            ...targets,
            ...this.findAllTargets(targetName),
            ...this.findAllLegacyTargets(targetName)
        ], []);
    }
    findTarget(targetName) {
        const selector = this.getSelectorForTargetName(targetName);
        return this.scope.findElement(selector);
    }
    findAllTargets(targetName) {
        const selector = this.getSelectorForTargetName(targetName);
        return this.scope.findAllElements(selector);
    }
    getSelectorForTargetName(targetName) {
        const attributeName = this.schema.targetAttributeForScope(this.identifier);
        return attributeValueContainsToken(attributeName, targetName);
    }
    findLegacyTarget(targetName) {
        const selector = this.getLegacySelectorForTargetName(targetName);
        return this.deprecate(this.scope.findElement(selector), targetName);
    }
    findAllLegacyTargets(targetName) {
        const selector = this.getLegacySelectorForTargetName(targetName);
        return this.scope.findAllElements(selector).map(element => this.deprecate(element, targetName));
    }
    getLegacySelectorForTargetName(targetName) {
        const targetDescriptor = `${this.identifier}.${targetName}`;
        return attributeValueContainsToken(this.schema.targetAttribute, targetDescriptor);
    }
    deprecate(element, targetName) {
        if (element) {
            const { identifier } = this;
            const attributeName = this.schema.targetAttribute;
            const revisedAttributeName = this.schema.targetAttributeForScope(identifier);
            this.guide.warn(element, `target:${targetName}`, `Please replace ${attributeName}="${identifier}.${targetName}" with ${revisedAttributeName}="${targetName}". ` +
                `The ${attributeName} attribute is deprecated and will be removed in a future version of Stimulus.`);
        }
        return element;
    }
    get guide() {
        return this.scope.guide;
    }
}

class Scope {
    constructor(schema, element, identifier, logger) {
        this.targets = new TargetSet(this);
        this.classes = new ClassMap(this);
        this.data = new DataMap(this);
        this.containsElement = (element) => {
            return element.closest(this.controllerSelector) === this.element;
        };
        this.schema = schema;
        this.element = element;
        this.identifier = identifier;
        this.guide = new Guide(logger);
    }
    findElement(selector) {
        return this.element.matches(selector)
            ? this.element
            : this.queryElements(selector).find(this.containsElement);
    }
    findAllElements(selector) {
        return [
            ...this.element.matches(selector) ? [this.element] : [],
            ...this.queryElements(selector).filter(this.containsElement)
        ];
    }
    queryElements(selector) {
        return Array.from(this.element.querySelectorAll(selector));
    }
    get controllerSelector() {
        return attributeValueContainsToken(this.schema.controllerAttribute, this.identifier);
    }
}

class ScopeObserver {
    constructor(element, schema, delegate) {
        this.element = element;
        this.schema = schema;
        this.delegate = delegate;
        this.valueListObserver = new ValueListObserver(this.element, this.controllerAttribute, this);
        this.scopesByIdentifierByElement = new WeakMap;
        this.scopeReferenceCounts = new WeakMap;
    }
    start() {
        this.valueListObserver.start();
    }
    stop() {
        this.valueListObserver.stop();
    }
    get controllerAttribute() {
        return this.schema.controllerAttribute;
    }
    parseValueForToken(token) {
        const { element, content: identifier } = token;
        const scopesByIdentifier = this.fetchScopesByIdentifierForElement(element);
        let scope = scopesByIdentifier.get(identifier);
        if (!scope) {
            scope = this.delegate.createScopeForElementAndIdentifier(element, identifier);
            scopesByIdentifier.set(identifier, scope);
        }
        return scope;
    }
    elementMatchedValue(element, value) {
        const referenceCount = (this.scopeReferenceCounts.get(value) || 0) + 1;
        this.scopeReferenceCounts.set(value, referenceCount);
        if (referenceCount == 1) {
            this.delegate.scopeConnected(value);
        }
    }
    elementUnmatchedValue(element, value) {
        const referenceCount = this.scopeReferenceCounts.get(value);
        if (referenceCount) {
            this.scopeReferenceCounts.set(value, referenceCount - 1);
            if (referenceCount == 1) {
                this.delegate.scopeDisconnected(value);
            }
        }
    }
    fetchScopesByIdentifierForElement(element) {
        let scopesByIdentifier = this.scopesByIdentifierByElement.get(element);
        if (!scopesByIdentifier) {
            scopesByIdentifier = new Map;
            this.scopesByIdentifierByElement.set(element, scopesByIdentifier);
        }
        return scopesByIdentifier;
    }
}

class Router {
    constructor(application) {
        this.application = application;
        this.scopeObserver = new ScopeObserver(this.element, this.schema, this);
        this.scopesByIdentifier = new Multimap;
        this.modulesByIdentifier = new Map;
    }
    get element() {
        return this.application.element;
    }
    get schema() {
        return this.application.schema;
    }
    get logger() {
        return this.application.logger;
    }
    get controllerAttribute() {
        return this.schema.controllerAttribute;
    }
    get modules() {
        return Array.from(this.modulesByIdentifier.values());
    }
    get contexts() {
        return this.modules.reduce((contexts, module) => contexts.concat(module.contexts), []);
    }
    start() {
        this.scopeObserver.start();
    }
    stop() {
        this.scopeObserver.stop();
    }
    loadDefinition(definition) {
        this.unloadIdentifier(definition.identifier);
        const module = new Module(this.application, definition);
        this.connectModule(module);
    }
    unloadIdentifier(identifier) {
        const module = this.modulesByIdentifier.get(identifier);
        if (module) {
            this.disconnectModule(module);
        }
    }
    getContextForElementAndIdentifier(element, identifier) {
        const module = this.modulesByIdentifier.get(identifier);
        if (module) {
            return module.contexts.find(context => context.element == element);
        }
    }
    handleError(error, message, detail) {
        this.application.handleError(error, message, detail);
    }
    createScopeForElementAndIdentifier(element, identifier) {
        return new Scope(this.schema, element, identifier, this.logger);
    }
    scopeConnected(scope) {
        this.scopesByIdentifier.add(scope.identifier, scope);
        const module = this.modulesByIdentifier.get(scope.identifier);
        if (module) {
            module.connectContextForScope(scope);
        }
    }
    scopeDisconnected(scope) {
        this.scopesByIdentifier.delete(scope.identifier, scope);
        const module = this.modulesByIdentifier.get(scope.identifier);
        if (module) {
            module.disconnectContextForScope(scope);
        }
    }
    connectModule(module) {
        this.modulesByIdentifier.set(module.identifier, module);
        const scopes = this.scopesByIdentifier.getValuesForKey(module.identifier);
        scopes.forEach(scope => module.connectContextForScope(scope));
    }
    disconnectModule(module) {
        this.modulesByIdentifier.delete(module.identifier);
        const scopes = this.scopesByIdentifier.getValuesForKey(module.identifier);
        scopes.forEach(scope => module.disconnectContextForScope(scope));
    }
}

const defaultSchema = {
    controllerAttribute: "data-controller",
    actionAttribute: "data-action",
    targetAttribute: "data-target",
    targetAttributeForScope: identifier => `data-${identifier}-target`
};

class Application {
    constructor(element = document.documentElement, schema = defaultSchema) {
        this.logger = console;
        this.debug = false;
        this.logDebugActivity = (identifier, functionName, detail = {}) => {
            if (this.debug) {
                this.logFormattedMessage(identifier, functionName, detail);
            }
        };
        this.element = element;
        this.schema = schema;
        this.dispatcher = new Dispatcher(this);
        this.router = new Router(this);
    }
    static start(element, schema) {
        const application = new Application(element, schema);
        application.start();
        return application;
    }
    async start() {
        await domReady();
        this.logDebugActivity("application", "starting");
        this.dispatcher.start();
        this.router.start();
        this.logDebugActivity("application", "start");
    }
    stop() {
        this.logDebugActivity("application", "stopping");
        this.dispatcher.stop();
        this.router.stop();
        this.logDebugActivity("application", "stop");
    }
    register(identifier, controllerConstructor) {
        if (controllerConstructor.shouldLoad) {
            this.load({ identifier, controllerConstructor });
        }
    }
    load(head, ...rest) {
        const definitions = Array.isArray(head) ? head : [head, ...rest];
        definitions.forEach(definition => this.router.loadDefinition(definition));
    }
    unload(head, ...rest) {
        const identifiers = Array.isArray(head) ? head : [head, ...rest];
        identifiers.forEach(identifier => this.router.unloadIdentifier(identifier));
    }
    get controllers() {
        return this.router.contexts.map(context => context.controller);
    }
    getControllerForElementAndIdentifier(element, identifier) {
        const context = this.router.getContextForElementAndIdentifier(element, identifier);
        return context ? context.controller : null;
    }
    handleError(error, message, detail) {
        var _a;
        this.logger.error(`%s\n\n%o\n\n%o`, message, error, detail);
        (_a = window.onerror) === null || _a === void 0 ? void 0 : _a.call(window, message, "", 0, 0, error);
    }
    logFormattedMessage(identifier, functionName, detail = {}) {
        detail = Object.assign({ application: this }, detail);
        this.logger.groupCollapsed(`${identifier} #${functionName}`);
        this.logger.log("details:", Object.assign({}, detail));
        this.logger.groupEnd();
    }
}
function domReady() {
    return new Promise(resolve => {
        if (document.readyState == "loading") {
            document.addEventListener("DOMContentLoaded", () => resolve());
        }
        else {
            resolve();
        }
    });
}

function ClassPropertiesBlessing(constructor) {
    const classes = readInheritableStaticArrayValues(constructor, "classes");
    return classes.reduce((properties, classDefinition) => {
        return Object.assign(properties, propertiesForClassDefinition(classDefinition));
    }, {});
}
function propertiesForClassDefinition(key) {
    return {
        [`${key}Class`]: {
            get() {
                const { classes } = this;
                if (classes.has(key)) {
                    return classes.get(key);
                }
                else {
                    const attribute = classes.getAttributeName(key);
                    throw new Error(`Missing attribute "${attribute}"`);
                }
            }
        },
        [`${key}Classes`]: {
            get() {
                return this.classes.getAll(key);
            }
        },
        [`has${capitalize(key)}Class`]: {
            get() {
                return this.classes.has(key);
            }
        }
    };
}

function TargetPropertiesBlessing(constructor) {
    const targets = readInheritableStaticArrayValues(constructor, "targets");
    return targets.reduce((properties, targetDefinition) => {
        return Object.assign(properties, propertiesForTargetDefinition(targetDefinition));
    }, {});
}
function propertiesForTargetDefinition(name) {
    return {
        [`${name}Target`]: {
            get() {
                const target = this.targets.find(name);
                if (target) {
                    return target;
                }
                else {
                    throw new Error(`Missing target element "${name}" for "${this.identifier}" controller`);
                }
            }
        },
        [`${name}Targets`]: {
            get() {
                return this.targets.findAll(name);
            }
        },
        [`has${capitalize(name)}Target`]: {
            get() {
                return this.targets.has(name);
            }
        }
    };
}

function ValuePropertiesBlessing(constructor) {
    const valueDefinitionPairs = readInheritableStaticObjectPairs(constructor, "values");
    const propertyDescriptorMap = {
        valueDescriptorMap: {
            get() {
                return valueDefinitionPairs.reduce((result, valueDefinitionPair) => {
                    const valueDescriptor = parseValueDefinitionPair(valueDefinitionPair);
                    const attributeName = this.data.getAttributeNameForKey(valueDescriptor.key);
                    return Object.assign(result, { [attributeName]: valueDescriptor });
                }, {});
            }
        }
    };
    return valueDefinitionPairs.reduce((properties, valueDefinitionPair) => {
        return Object.assign(properties, propertiesForValueDefinitionPair(valueDefinitionPair));
    }, propertyDescriptorMap);
}
function propertiesForValueDefinitionPair(valueDefinitionPair) {
    const definition = parseValueDefinitionPair(valueDefinitionPair);
    const { key, name, reader: read, writer: write } = definition;
    return {
        [name]: {
            get() {
                const value = this.data.get(key);
                if (value !== null) {
                    return read(value);
                }
                else {
                    return definition.defaultValue;
                }
            },
            set(value) {
                if (value === undefined) {
                    this.data.delete(key);
                }
                else {
                    this.data.set(key, write(value));
                }
            }
        },
        [`has${capitalize(name)}`]: {
            get() {
                return this.data.has(key) || definition.hasCustomDefaultValue;
            }
        }
    };
}
function parseValueDefinitionPair([token, typeDefinition]) {
    return valueDescriptorForTokenAndTypeDefinition(token, typeDefinition);
}
function parseValueTypeConstant(constant) {
    switch (constant) {
        case Array: return "array";
        case Boolean: return "boolean";
        case Number: return "number";
        case Object: return "object";
        case String: return "string";
    }
}
function parseValueTypeDefault(defaultValue) {
    switch (typeof defaultValue) {
        case "boolean": return "boolean";
        case "number": return "number";
        case "string": return "string";
    }
    if (Array.isArray(defaultValue))
        return "array";
    if (Object.prototype.toString.call(defaultValue) === "[object Object]")
        return "object";
}
function parseValueTypeObject(typeObject) {
    const typeFromObject = parseValueTypeConstant(typeObject.type);
    if (typeFromObject) {
        const defaultValueType = parseValueTypeDefault(typeObject.default);
        if (typeFromObject !== defaultValueType) {
            throw new Error(`Type "${typeFromObject}" must match the type of the default value. Given default value: "${typeObject.default}" as "${defaultValueType}"`);
        }
        return typeFromObject;
    }
}
function parseValueTypeDefinition(typeDefinition) {
    const typeFromObject = parseValueTypeObject(typeDefinition);
    const typeFromDefaultValue = parseValueTypeDefault(typeDefinition);
    const typeFromConstant = parseValueTypeConstant(typeDefinition);
    const type = typeFromObject || typeFromDefaultValue || typeFromConstant;
    if (type)
        return type;
    throw new Error(`Unknown value type "${typeDefinition}"`);
}
function defaultValueForDefinition(typeDefinition) {
    const constant = parseValueTypeConstant(typeDefinition);
    if (constant)
        return defaultValuesByType[constant];
    const defaultValue = typeDefinition.default;
    if (defaultValue !== undefined)
        return defaultValue;
    return typeDefinition;
}
function valueDescriptorForTokenAndTypeDefinition(token, typeDefinition) {
    const key = `${dasherize(token)}-value`;
    const type = parseValueTypeDefinition(typeDefinition);
    return {
        type,
        key,
        name: camelize(key),
        get defaultValue() { return defaultValueForDefinition(typeDefinition); },
        get hasCustomDefaultValue() { return parseValueTypeDefault(typeDefinition) !== undefined; },
        reader: readers[type],
        writer: writers[type] || writers.default
    };
}
const defaultValuesByType = {
    get array() { return []; },
    boolean: false,
    number: 0,
    get object() { return {}; },
    string: ""
};
const readers = {
    array(value) {
        const array = JSON.parse(value);
        if (!Array.isArray(array)) {
            throw new TypeError("Expected array");
        }
        return array;
    },
    boolean(value) {
        return !(value == "0" || value == "false");
    },
    number(value) {
        return Number(value);
    },
    object(value) {
        const object = JSON.parse(value);
        if (object === null || typeof object != "object" || Array.isArray(object)) {
            throw new TypeError("Expected object");
        }
        return object;
    },
    string(value) {
        return value;
    }
};
const writers = {
    default: writeString,
    array: writeJSON,
    object: writeJSON
};
function writeJSON(value) {
    return JSON.stringify(value);
}
function writeString(value) {
    return `${value}`;
}

class Controller {
    constructor(context) {
        this.context = context;
    }
    static get shouldLoad() {
        return true;
    }
    get application() {
        return this.context.application;
    }
    get scope() {
        return this.context.scope;
    }
    get element() {
        return this.scope.element;
    }
    get identifier() {
        return this.scope.identifier;
    }
    get targets() {
        return this.scope.targets;
    }
    get classes() {
        return this.scope.classes;
    }
    get data() {
        return this.scope.data;
    }
    initialize() {
    }
    connect() {
    }
    disconnect() {
    }
    dispatch(eventName, { target = this.element, detail = {}, prefix = this.identifier, bubbles = true, cancelable = true } = {}) {
        const type = prefix ? `${prefix}:${eventName}` : eventName;
        const event = new CustomEvent(type, { detail, bubbles, cancelable });
        target.dispatchEvent(event);
        return event;
    }
}
Controller.blessings = [ClassPropertiesBlessing, TargetPropertiesBlessing, ValuePropertiesBlessing];
Controller.targets = [];
Controller.values = {};




/***/ }),

/***/ "../../../packages/dragon-nest/dist/index.es.js":
/*!******************************************************!*\
  !*** ../../../packages/dragon-nest/dist/index.es.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _default)
/* harmony export */ });
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e3) { throw _e3; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e4) { didErr = true; err = _e4; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var _default = /*#__PURE__*/function () {
  function _default(t) {
    var _this = this;

    var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, _default);

    this.dragstart = function (t) {
      if (t.defaultPrevented) return;
      t.stopPropagation(), _this.dragging = t.target;

      var e = _this.dragging.getBoundingClientRect();

      _this.offsetX = t.clientX - e.x, _this.offsetY = t.clientY - e.y, document.addEventListener("dragover", _this.dragover);
    }, this.dragend = function () {
      delete _this.dragging, document.removeEventListener("dragover", _this.dragover);
    }, this.dragover = function (t) {
      t.preventDefault();

      var e = Array.from(_this.list.querySelectorAll('[draggable="true"]')).filter(function (t) {
        return t !== _this.dragging;
      }),
          n = _this.dragging.getBoundingClientRect(),
          i = t.clientY - (_this.offsetY || 0),
          s = t.clientY - (_this.offsetY || 0) + n.height;

      var _iterator = _createForOfIteratorHelper(e),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var _t3 = _step.value;

          var _e = _t3.firstElementChild.getBoundingClientRect();

          if (i > _e.top && i < _e.top + _e.height / 2) {
            _this.canMove(_t3.parentElement) && _t3.parentElement.insertBefore(_this.dragging, _t3);
            break;
          }

          if (s < _e.bottom && s > _e.bottom - _e.height / 2) {
            var _e2 = _t3.querySelector(':scope > ul, :scope > ol, :scope > [role="list"]');

            _e2 && _this.canMove(_e2) ? _e2.prepend(_this.dragging) : _this.canMove(_t3.parentElement) && _t3.parentElement.insertBefore(_this.dragging, _t3.nextElementSibling);
            break;
          }
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      var r = t.clientX - (_this.offsetX || 0);

      if (_this.dragging.previousElementSibling && r > n.left + _this.options.indent) {
        var _t = _this.dragging.previousElementSibling.querySelector(':scope > ul, :scope > ol, :scope > [role="list"]');

        _t && _this.canMove(_t) && _t.appendChild(_this.dragging);
      } else if (!_this.dragging.nextElementSibling && r < n.left && _this.dragging.parentNode !== _this.list) {
        var _t2 = _this.dragging.parentElement.parentElement.parentElement;
        _this.canMove(_t2) && _t2.insertBefore(_this.dragging, _this.dragging.parentElement.parentElement.nextElementSibling);
      }
    }, this.list = t, this.options = Object.assign({
      indent: 40
    }, e), this.list.addEventListener("dragstart", this.dragstart), this.list.addEventListener("dragend", this.dragend);
  }

  _createClass(_default, [{
    key: "destroy",
    value: function destroy() {
      this.list.removeEventListener("dragstart", this.dragstart), this.list.removeEventListener("dragend", this.dragend);
    }
  }, {
    key: "canMove",
    value: function canMove(t) {
      var e = new CustomEvent("dragon-nest:move", {
        bubbles: !0,
        cancelable: !0,
        detail: {
          parent: t
        }
      });
      return this.dragging.dispatchEvent(e), !e.defaultPrevented;
    }
  }]);

  return _default;
}();



/***/ }),

/***/ "./resources/js/admin/controllers/admin-structure.ts":
/*!***********************************************************!*\
  !*** ./resources/js/admin/controllers/admin-structure.ts ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ default_1)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }



var default_1 = /*#__PURE__*/function (_Controller) {
  _inherits(default_1, _Controller);

  var _super = _createSuper(default_1);

  function default_1() {
    _classCallCheck(this, default_1);

    return _super.apply(this, arguments);
  }

  _createClass(default_1, [{
    key: "saveOrder",
    value: function saveOrder() {
      var _this = this;

      setTimeout(function () {
        _this.orderFormTarget.requestSubmit();
      });
    }
  }]);

  return default_1;
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);


default_1.targets = ['orderForm'];

/***/ }),

/***/ "./resources/js/admin/controllers/color-picker.ts":
/*!********************************************************!*\
  !*** ./resources/js/admin/controllers/color-picker.ts ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ default_1)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }



var default_1 = /*#__PURE__*/function (_Controller) {
  _inherits(default_1, _Controller);

  var _super = _createSuper(default_1);

  function default_1() {
    _classCallCheck(this, default_1);

    return _super.apply(this, arguments);
  }

  _createClass(default_1, [{
    key: "colorChanged",
    value: function colorChanged(e) {
      this.inputTarget.color = e.detail.value;
      this.pickerTarget.color = e.detail.value;
      this.swatchTarget.style.backgroundColor = e.detail.value;
    }
  }, {
    key: "show",
    value: function show() {
      this.pickerTarget.hidden = false;
    }
  }, {
    key: "hide",
    value: function hide() {
      this.pickerTarget.hidden = true;
    }
  }]);

  return default_1;
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);


default_1.targets = ['input', 'picker', 'swatch'];

/***/ }),

/***/ "./resources/js/admin/controllers/dragon-nest.ts":
/*!*******************************************************!*\
  !*** ./resources/js/admin/controllers/dragon-nest.ts ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ default_1)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
/* harmony import */ var dragon_nest__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! dragon-nest */ "../../../packages/dragon-nest/dist/index.es.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }




var default_1 = /*#__PURE__*/function (_Controller) {
  _inherits(default_1, _Controller);

  var _super = _createSuper(default_1);

  function default_1() {
    var _this;

    _classCallCheck(this, default_1);

    _this = _super.apply(this, arguments);

    _this.mousedown = function (e) {
      _this.dragTarget = e.target;
    };

    _this.start = function (e) {
      var _a;

      if ((_a = _this.dragTarget) === null || _a === void 0 ? void 0 : _a.closest('[data-handle]')) {
        e.target.classList.add('is-dragging');
      } else {
        e.preventDefault();
      }
    };

    _this.end = function (e) {
      e.target.classList.remove('is-dragging');

      var nodes = _this.listTarget.querySelectorAll('[data-id]');

      var result = Array.from(nodes).map(function (el, i) {
        return el.dataset.id;
      });
      _this.orderInputTarget.value = JSON.stringify(result);
    };

    return _this;
  }

  _createClass(default_1, [{
    key: "connect",
    value: function connect() {
      this.dragonNest = new dragon_nest__WEBPACK_IMPORTED_MODULE_1__["default"](this.listTarget);
      this.listTarget.addEventListener('dragstart', this.start);
      this.listTarget.addEventListener('dragend', this.end);
      document.addEventListener('mousedown', this.mousedown);
    }
  }, {
    key: "disconnect",
    value: function disconnect() {
      var _a;

      (_a = this.dragonNest) === null || _a === void 0 ? void 0 : _a.destroy();
      this.listTarget.removeEventListener('dragstart', this.start);
      this.listTarget.removeEventListener('dragend', this.end);
      document.removeEventListener('mousedown', this.mousedown);
    }
  }]);

  return default_1;
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);


default_1.targets = ['list', 'orderInput'];

/***/ }),

/***/ "./resources/js/admin/controllers/permission-grid.ts":
/*!***********************************************************!*\
  !*** ./resources/js/admin/controllers/permission-grid.ts ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _default)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }



var _default = /*#__PURE__*/function (_Controller) {
  _inherits(_default, _Controller);

  var _super = _createSuper(_default);

  function _default() {
    _classCallCheck(this, _default);

    return _super.apply(this, arguments);
  }

  _createClass(_default, [{
    key: "connect",
    value: function connect() {
      this.disabled = Array.from(this.element.querySelectorAll('input:disabled'));
      this.update();
    }
  }, {
    key: "mouseover",
    value: function mouseover(e) {
      var target = e.target;

      if (target.matches('thead th, thead th *')) {
        var index = Array.from(target.parentElement.children).indexOf(target);
        this.element.querySelector('colgroup').children[index].classList.add('is-highlighted');
        this.element.style.cursor = 'pointer';
      }

      if (target.matches('tbody th, tbody th *')) {
        target.closest('tr').classList.add('is-highlighted');
        this.element.style.cursor = 'pointer';
      }
    }
  }, {
    key: "reset",
    value: function reset(e) {
      this.element.querySelectorAll('col, tr').forEach(function (el) {
        return el.classList.remove('is-highlighted');
      });
      this.element.style.cursor = '';
    }
  }, {
    key: "click",
    value: function click(e) {
      var _this = this;

      var _a, _b;

      var target = e.target;

      if (target.matches('thead th, thead th *')) {
        var index = Array.from(target.parentElement.children).indexOf(target);
        var checkboxes = Array.from(this.element.querySelectorAll("tbody tr td:nth-child(".concat(index + 1, ") input[type=\"checkbox\"]"))).filter(function (checkbox) {
          var _a;

          return !((_a = _this.disabled) === null || _a === void 0 ? void 0 : _a.includes(checkbox));
        });
        var checked = !((_a = checkboxes.find(function (checkbox) {
          return !checkbox.disabled && checkbox.getAttribute('aria-disabled') !== 'true';
        })) === null || _a === void 0 ? void 0 : _a.checked);
        checkboxes.forEach(function (el) {
          return el.checked = checked;
        });
      }

      if (target.matches('tbody th, tbody th *')) {
        var _checkboxes = Array.from(target.closest('tr').querySelectorAll("td input[type=\"checkbox\"]")).filter(function (checkbox) {
          var _a;

          return !((_a = _this.disabled) === null || _a === void 0 ? void 0 : _a.includes(checkbox));
        });

        var _checked = !((_b = _checkboxes.find(function (checkbox) {
          return !checkbox.disabled && checkbox.getAttribute('aria-disabled') !== 'true';
        })) === null || _b === void 0 ? void 0 : _b.checked);

        _checkboxes.forEach(function (el) {
          return el.checked = _checked;
        });
      }

      this.update();
    }
  }, {
    key: "update",
    value: function update() {
      var _this2 = this;

      this.element.querySelectorAll('tbody td input[type="checkbox"]').forEach(function (checkbox) {
        var _a;

        if (!((_a = _this2.disabled) === null || _a === void 0 ? void 0 : _a.includes(checkbox))) {
          checkbox.disabled = false; //setAttribute('aria-disabled', 'false');
        }
      });
      this.element.querySelectorAll('[data-implied-by], [data-depends-on]').forEach(function (el) {
        if (el.dataset.impliedBy) {
          el.dataset.impliedBy.trim().split(/\s+/).filter(Boolean).forEach(function (name) {
            var ref = document.querySelector("[name=\"".concat(name, "\"]:last-of-type"));

            if (ref && ref.checked) {
              el.checked = true;
              el.disabled = true; //setAttribute('aria-disabled', 'true');
            }
          });
        }

        if (el.dataset.dependsOn) {
          el.dataset.dependsOn.trim().split(/\s+/).filter(Boolean).forEach(function (name) {
            var ref = document.querySelector("[name=\"".concat(name, "\"]:last-of-type"));

            if (ref && !ref.checked) {
              el.checked = false;
              el.disabled = true; //setAttribute('aria-disabled', 'true');
            }
          });
        }
      }); // [1, 2].forEach((id, i) => {
      //     Array.from(this.element.querySelectorAll<HTMLInputElement>(`[data-group-id="${id}"] td input`))
      //         .filter(checkbox => ! this.disabled?.includes(checkbox))
      //         .forEach(checkbox => {
      //             const cell = checkbox.closest('td')!;
      //             const index = Array.from(cell.parentElement!.children).indexOf(cell);
      //             const rows = Array.from(this.element.querySelectorAll<HTMLInputElement>('tbody tr')).slice(i + 1);
      //             rows.forEach(row => {
      //                 const input = row.querySelector<HTMLInputElement>(`td:nth-child(${index + 1}) input`)!;
      //                 if (checkbox.checked) {
      //                     input.checked = true;
      //                 }
      //             });
      //         });
      // });
      //
      // this.element.querySelectorAll('tbody tr').forEach(row => {
      //     const inputs = Array.from(row.querySelectorAll<HTMLInputElement>('td input'));
      //     if (! inputs[0].checked) {
      //         inputs.slice(1).forEach(checkbox => {
      //             checkbox.checked = false;
      //         });
      //     }
      // });
      //
      //
      // [1, 2].forEach((id, i) => {
      //     Array.from(this.element.querySelectorAll<HTMLInputElement>(`[data-group-id="${id}"] td input`))
      //         .filter(checkbox => ! this.disabled?.includes(checkbox))
      //         .forEach(checkbox => {
      //             const cell = checkbox.closest('td')!;
      //             const index = Array.from(cell.parentElement!.children).indexOf(cell);
      //             const rows = Array.from(this.element.querySelectorAll<HTMLInputElement>('tbody tr')).slice(i + 1);
      //             rows.forEach(row => {
      //                 const input = row.querySelector<HTMLInputElement>(`td:nth-child(${index + 1}) input`)!;
      //                 if (checkbox.checked) {
      //                     input.setAttribute('aria-disabled', 'true');
      //                 }
      //             });
      //         });
      // });
      //
      // this.element.querySelectorAll('tbody tr').forEach(row => {
      //     const inputs = Array.from(row.querySelectorAll<HTMLInputElement>('td input'));
      //     if (! inputs[0].checked) {
      //         inputs.slice(1).forEach(checkbox => {
      //             checkbox.setAttribute('aria-disabled', 'true');
      //         });
      //     }
      // });
    }
  }]);

  return _default;
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);



/***/ }),

/***/ "./resources/js/admin/controllers/slugger.ts":
/*!***************************************************!*\
  !*** ./resources/js/admin/controllers/slugger.ts ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ default_1)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils */ "./resources/js/utils.ts");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }




var default_1 = /*#__PURE__*/function (_Controller) {
  _inherits(default_1, _Controller);

  var _super = _createSuper(default_1);

  function default_1() {
    var _this;

    _classCallCheck(this, default_1);

    _this = _super.apply(this, arguments);
    _this.locked = false;
    return _this;
  }

  _createClass(default_1, [{
    key: "updateName",
    value: function updateName(e) {
      var input = e.target;

      if (!this.locked) {
        this.slugTarget.value = (0,_utils__WEBPACK_IMPORTED_MODULE_1__.slug)(input.value);
        this.mirror();
      }
    }
  }, {
    key: "updateSlug",
    value: function updateSlug(e) {
      var input = e.target;
      this.mirror();
      this.locked = Boolean(input.value);
    }
  }, {
    key: "mirror",
    value: function mirror() {
      var _this2 = this;

      var _a;

      (_a = this.mirrorTargets) === null || _a === void 0 ? void 0 : _a.forEach(function (el) {
        el.textContent = _this2.slugTarget.value;
      });
    }
  }]);

  return default_1;
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);


default_1.targets = ['slug', 'mirror'];

/***/ }),

/***/ "./resources/js/utils.ts":
/*!*******************************!*\
  !*** ./resources/js/utils.ts ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "shouldOpenInNewTab": () => (/* binding */ shouldOpenInNewTab),
/* harmony export */   "isElementInViewport": () => (/* binding */ isElementInViewport),
/* harmony export */   "getHeaderHeight": () => (/* binding */ getHeaderHeight),
/* harmony export */   "getCookieValue": () => (/* binding */ getCookieValue),
/* harmony export */   "htmlToElement": () => (/* binding */ htmlToElement),
/* harmony export */   "slug": () => (/* binding */ slug)
/* harmony export */ });
function shouldOpenInNewTab(e) {
  return e.altKey || e.ctrlKey || e.metaKey || e.shiftKey || e.button !== undefined && e.button !== 0;
}
function isElementInViewport(el) {
  var proportion = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
  var rect = el.getBoundingClientRect();
  return -rect.top / rect.height < proportion && (rect.bottom - window.innerHeight) / rect.height < proportion;
}
function getHeaderHeight() {
  var _a;

  return ((_a = document.querySelector('.header')) === null || _a === void 0 ? void 0 : _a.offsetHeight) || 0;
}
function getCookieValue(name) {
  var cookies = document.cookie ? document.cookie.split('; ') : [];
  var cookie = cookies.find(function (cookie) {
    return cookie.startsWith(name);
  });

  if (cookie) {
    var value = cookie.split('=').slice(1).join('=');
    return value ? decodeURIComponent(value) : undefined;
  }
}
function htmlToElement(html) {
  var template = document.createElement('template');
  template.innerHTML = html;
  return template.content.firstElementChild;
}
/**
 * Create a slug out of the given string. Non-alphanumeric characters are
 * converted to hyphens.
 */

function slug(string) {
  return string.toLowerCase().replace(/[^a-z0-9]/gi, '-').replace(/-+/g, '-').replace(/-$|^-/g, '');
}

/***/ }),

/***/ "./node_modules/vanilla-colorful/hex-color-picker.js":
/*!***********************************************************!*\
  !*** ./node_modules/vanilla-colorful/hex-color-picker.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "HexColorPicker": () => (/* binding */ HexColorPicker)
/* harmony export */ });
/* harmony import */ var _lib_entrypoints_hex_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./lib/entrypoints/hex.js */ "./node_modules/vanilla-colorful/lib/entrypoints/hex.js");

/**
 * A color picker custom element that uses HEX format.
 *
 * @element hex-color-picker
 *
 * @prop {string} color - Selected color in HEX format.
 * @attr {string} color - Selected color in HEX format.
 *
 * @fires color-changed - Event fired when color property changes.
 *
 * @csspart hue - A hue selector container.
 * @csspart saturation - A saturation selector container
 * @csspart hue-pointer - A hue pointer element.
 * @csspart saturation-pointer - A saturation pointer element.
 */
class HexColorPicker extends _lib_entrypoints_hex_js__WEBPACK_IMPORTED_MODULE_0__.HexBase {
}
customElements.define('hex-color-picker', HexColorPicker);
//# sourceMappingURL=hex-color-picker.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/hex-input.js":
/*!****************************************************!*\
  !*** ./node_modules/vanilla-colorful/hex-input.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "HexInput": () => (/* binding */ HexInput)
/* harmony export */ });
/* harmony import */ var _lib_entrypoints_hex_input_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./lib/entrypoints/hex-input.js */ "./node_modules/vanilla-colorful/lib/entrypoints/hex-input.js");

/**
 * A color picker custom element that uses HEX format.
 *
 * @element hex-input
 *
 * @prop {string} color - Color in HEX format.
 * @attr {string} color - Selected color in HEX format.
 *
 * @fires color-changed - Event fired when color is changed.
 *
 * @csspart input - A native input element.
 */
class HexInput extends _lib_entrypoints_hex_input_js__WEBPACK_IMPORTED_MODULE_0__.HexInputBase {
}
customElements.define('hex-input', HexInput);
//# sourceMappingURL=hex-input.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/components/color-picker.js":
/*!**********************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/components/color-picker.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "$css": () => (/* binding */ $css),
/* harmony export */   "$sliders": () => (/* binding */ $sliders),
/* harmony export */   "ColorPicker": () => (/* binding */ ColorPicker)
/* harmony export */ });
/* harmony import */ var _utils_compare_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../utils/compare.js */ "./node_modules/vanilla-colorful/lib/utils/compare.js");
/* harmony import */ var _utils_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils/dom.js */ "./node_modules/vanilla-colorful/lib/utils/dom.js");
/* harmony import */ var _hue_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./hue.js */ "./node_modules/vanilla-colorful/lib/components/hue.js");
/* harmony import */ var _saturation_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./saturation.js */ "./node_modules/vanilla-colorful/lib/components/saturation.js");
/* harmony import */ var _styles_color_picker_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../styles/color-picker.js */ "./node_modules/vanilla-colorful/lib/styles/color-picker.js");
/* harmony import */ var _styles_hue_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../styles/hue.js */ "./node_modules/vanilla-colorful/lib/styles/hue.js");
/* harmony import */ var _styles_saturation_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../styles/saturation.js */ "./node_modules/vanilla-colorful/lib/styles/saturation.js");







const $isSame = Symbol('same');
const $color = Symbol('color');
const $hsva = Symbol('hsva');
const $change = Symbol('change');
const $update = Symbol('update');
const $parts = Symbol('parts');
const $css = Symbol('css');
const $sliders = Symbol('sliders');
class ColorPicker extends HTMLElement {
    static get observedAttributes() {
        return ['color'];
    }
    get [$css]() {
        return [_styles_color_picker_js__WEBPACK_IMPORTED_MODULE_0__["default"], _styles_hue_js__WEBPACK_IMPORTED_MODULE_1__["default"], _styles_saturation_js__WEBPACK_IMPORTED_MODULE_2__["default"]];
    }
    get [$sliders]() {
        return [_saturation_js__WEBPACK_IMPORTED_MODULE_3__.Saturation, _hue_js__WEBPACK_IMPORTED_MODULE_4__.Hue];
    }
    get color() {
        return this[$color];
    }
    set color(newColor) {
        if (!this[$isSame](newColor)) {
            const newHsva = this.colorModel.toHsva(newColor);
            this[$update](newHsva);
            this[$change](newColor);
        }
    }
    constructor() {
        super();
        const template = (0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_5__.tpl)(`<style>${this[$css].join('')}</style>`);
        const root = this.attachShadow({ mode: 'open' });
        root.appendChild(template.content.cloneNode(true));
        root.addEventListener('move', this);
        this[$parts] = this[$sliders].map((slider) => new slider(root));
    }
    connectedCallback() {
        // A user may set a property on an _instance_ of an element,
        // before its prototype has been connected to this class.
        // If so, we need to run it through the proper class setter.
        if (this.hasOwnProperty('color')) {
            const value = this.color;
            delete this['color'];
            this.color = value;
        }
        else if (!this.color) {
            this.color = this.colorModel.defaultColor;
        }
    }
    attributeChangedCallback(_attr, _oldVal, newVal) {
        const color = this.colorModel.fromAttr(newVal);
        if (!this[$isSame](color)) {
            this.color = color;
        }
    }
    handleEvent(event) {
        // Merge the current HSV color object with updated params.
        const oldHsva = this[$hsva];
        const newHsva = { ...oldHsva, ...event.detail };
        this[$update](newHsva);
        let newColor;
        if (!(0,_utils_compare_js__WEBPACK_IMPORTED_MODULE_6__.equalColorObjects)(newHsva, oldHsva) &&
            !this[$isSame]((newColor = this.colorModel.fromHsva(newHsva)))) {
            this[$change](newColor);
        }
    }
    [$isSame](color) {
        return this.color && this.colorModel.equal(color, this.color);
    }
    [$update](hsva) {
        this[$hsva] = hsva;
        this[$parts].forEach((part) => part.update(hsva));
    }
    [$change](value) {
        this[$color] = value;
        (0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_5__.fire)(this, 'color-changed', { value });
    }
}
//# sourceMappingURL=color-picker.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/components/hue.js":
/*!*************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/components/hue.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Hue": () => (/* binding */ Hue)
/* harmony export */ });
/* harmony import */ var _slider_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./slider.js */ "./node_modules/vanilla-colorful/lib/components/slider.js");
/* harmony import */ var _utils_convert_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/convert.js */ "./node_modules/vanilla-colorful/lib/utils/convert.js");
/* harmony import */ var _utils_math_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/math.js */ "./node_modules/vanilla-colorful/lib/utils/math.js");



class Hue extends _slider_js__WEBPACK_IMPORTED_MODULE_0__.Slider {
    constructor(root) {
        super(root, 'hue', 'aria-label="Hue" aria-valuemin="0" aria-valuemax="360"', false);
    }
    update({ h }) {
        this.h = h;
        this.style([
            {
                left: `${(h / 360) * 100}%`,
                color: (0,_utils_convert_js__WEBPACK_IMPORTED_MODULE_1__.hsvaToHslString)({ h, s: 100, v: 100, a: 1 })
            }
        ]);
        this.el.setAttribute('aria-valuenow', `${(0,_utils_math_js__WEBPACK_IMPORTED_MODULE_2__.round)(h)}`);
    }
    getMove(offset, key) {
        // Hue measured in degrees of the color circle ranging from 0 to 360
        return { h: key ? (0,_utils_math_js__WEBPACK_IMPORTED_MODULE_2__.clamp)(this.h + offset.x * 360, 0, 360) : 360 * offset.x };
    }
}
//# sourceMappingURL=hue.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/components/saturation.js":
/*!********************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/components/saturation.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Saturation": () => (/* binding */ Saturation)
/* harmony export */ });
/* harmony import */ var _slider_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./slider.js */ "./node_modules/vanilla-colorful/lib/components/slider.js");
/* harmony import */ var _utils_convert_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/convert.js */ "./node_modules/vanilla-colorful/lib/utils/convert.js");
/* harmony import */ var _utils_math_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/math.js */ "./node_modules/vanilla-colorful/lib/utils/math.js");



class Saturation extends _slider_js__WEBPACK_IMPORTED_MODULE_0__.Slider {
    constructor(root) {
        super(root, 'saturation', 'aria-label="Color"', true);
    }
    update(hsva) {
        this.hsva = hsva;
        this.style([
            {
                top: `${100 - hsva.v}%`,
                left: `${hsva.s}%`,
                color: (0,_utils_convert_js__WEBPACK_IMPORTED_MODULE_1__.hsvaToHslString)(hsva)
            },
            {
                'background-color': (0,_utils_convert_js__WEBPACK_IMPORTED_MODULE_1__.hsvaToHslString)({ h: hsva.h, s: 100, v: 100, a: 1 })
            }
        ]);
        this.el.setAttribute('aria-valuetext', `Saturation ${(0,_utils_math_js__WEBPACK_IMPORTED_MODULE_2__.round)(hsva.s)}%, Brightness ${(0,_utils_math_js__WEBPACK_IMPORTED_MODULE_2__.round)(hsva.v)}%`);
    }
    getMove(offset, key) {
        // Saturation and brightness always fit into [0, 100] range
        return {
            s: key ? (0,_utils_math_js__WEBPACK_IMPORTED_MODULE_2__.clamp)(this.hsva.s + offset.x * 100, 0, 100) : offset.x * 100,
            v: key ? (0,_utils_math_js__WEBPACK_IMPORTED_MODULE_2__.clamp)(this.hsva.v - offset.y * 100, 0, 100) : Math.round(100 - offset.y * 100)
        };
    }
}
//# sourceMappingURL=saturation.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/components/slider.js":
/*!****************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/components/slider.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Slider": () => (/* binding */ Slider)
/* harmony export */ });
/* harmony import */ var _utils_dom_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/dom.js */ "./node_modules/vanilla-colorful/lib/utils/dom.js");
/* harmony import */ var _utils_math_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/math.js */ "./node_modules/vanilla-colorful/lib/utils/math.js");


let hasTouched = false;
// Check if an event was triggered by touch
const isTouch = (e) => 'touches' in e;
// Prevent mobile browsers from handling mouse events (conflicting with touch ones).
// If we detected a touch interaction before, we prefer reacting to touch events only.
const isValid = (event) => {
    if (hasTouched && !isTouch(event))
        return false;
    if (!hasTouched)
        hasTouched = isTouch(event);
    return true;
};
const pointerMove = (target, event) => {
    const pointer = isTouch(event) ? event.touches[0] : event;
    const rect = target.el.getBoundingClientRect();
    (0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_0__.fire)(target.el, 'move', target.getMove({
        x: (0,_utils_math_js__WEBPACK_IMPORTED_MODULE_1__.clamp)((pointer.pageX - (rect.left + window.pageXOffset)) / rect.width),
        y: (0,_utils_math_js__WEBPACK_IMPORTED_MODULE_1__.clamp)((pointer.pageY - (rect.top + window.pageYOffset)) / rect.height)
    }));
};
const keyMove = (target, event) => {
    // We use `keyCode` instead of `key` to reduce the size of the library.
    const keyCode = event.keyCode;
    // Ignore all keys except arrow ones, Page Up, Page Down, Home and End.
    if (keyCode > 40 || (target.xy && keyCode < 37) || keyCode < 33)
        return;
    // Do not scroll page by keys when color picker element has focus.
    event.preventDefault();
    // Send relative offset to the parent component.
    (0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_0__.fire)(target.el, 'move', target.getMove({
        x: keyCode === 39 // Arrow Right
            ? 0.01
            : keyCode === 37 // Arrow Left
                ? -0.01
                : keyCode === 34 // Page Down
                    ? 0.05
                    : keyCode === 33 // Page Up
                        ? -0.05
                        : keyCode === 35 // End
                            ? 1
                            : keyCode === 36 // Home
                                ? -1
                                : 0,
        y: keyCode === 40 // Arrow down
            ? 0.01
            : keyCode === 38 // Arrow Up
                ? -0.01
                : 0
    }, true));
};
class Slider {
    constructor(root, part, aria, xy) {
        const template = (0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_0__.tpl)(`<div role="slider" tabindex="0" part="${part}" ${aria}><div part="${part}-pointer"></div></div>`);
        root.appendChild(template.content.cloneNode(true));
        const el = root.querySelector(`[part=${part}]`);
        el.addEventListener('mousedown', this);
        el.addEventListener('touchstart', this);
        el.addEventListener('keydown', this);
        this.el = el;
        this.xy = xy;
        this.nodes = [el.firstChild, el];
    }
    set dragging(state) {
        const toggleEvent = state ? document.addEventListener : document.removeEventListener;
        toggleEvent(hasTouched ? 'touchmove' : 'mousemove', this);
        toggleEvent(hasTouched ? 'touchend' : 'mouseup', this);
    }
    handleEvent(event) {
        switch (event.type) {
            case 'mousedown':
            case 'touchstart':
                event.preventDefault();
                // event.button is 0 in mousedown for left button activation
                if (!isValid(event) || (!hasTouched && event.button != 0))
                    return;
                this.el.focus();
                pointerMove(this, event);
                this.dragging = true;
                break;
            case 'mousemove':
            case 'touchmove':
                event.preventDefault();
                pointerMove(this, event);
                break;
            case 'mouseup':
            case 'touchend':
                this.dragging = false;
                break;
            case 'keydown':
                keyMove(this, event);
                break;
        }
    }
    style(styles) {
        styles.forEach((style, i) => {
            for (const p in style) {
                this.nodes[i].style.setProperty(p, style[p]);
            }
        });
    }
}
//# sourceMappingURL=slider.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/entrypoints/hex-input.js":
/*!********************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/entrypoints/hex-input.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "HexInputBase": () => (/* binding */ HexInputBase)
/* harmony export */ });
/* harmony import */ var _utils_validate_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/validate.js */ "./node_modules/vanilla-colorful/lib/utils/validate.js");
/* harmony import */ var _utils_dom_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/dom.js */ "./node_modules/vanilla-colorful/lib/utils/dom.js");


const template = (0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_0__.tpl)('<slot><input part="input" spellcheck="false"></slot>');
// Escapes all non-hexadecimal characters including "#"
const escape = (hex) => hex.replace(/([^0-9A-F]+)/gi, '').substr(0, 6);
const $color = Symbol('color');
const $saved = Symbol('saved');
const $input = Symbol('saved');
const $update = Symbol('update');
class HexInputBase extends HTMLElement {
    static get observedAttributes() {
        return ['color'];
    }
    get color() {
        return this[$color];
    }
    set color(hex) {
        this[$color] = hex;
        this[$update](hex);
    }
    connectedCallback() {
        const root = this.attachShadow({ mode: 'open' });
        root.appendChild(template.content.cloneNode(true));
        const slot = root.firstElementChild;
        const setInput = () => {
            let input = this.querySelector('input');
            if (!input) {
                // remove all child node if no input found
                let c;
                while ((c = this.firstChild)) {
                    c.remove();
                }
                input = slot.firstChild;
            }
            input.addEventListener('input', this);
            input.addEventListener('blur', this);
            this[$input] = input;
        };
        slot.addEventListener('slotchange', setInput);
        setInput();
        // A user may set a property on an _instance_ of an element,
        // before its prototype has been connected to this class.
        // If so, we need to run it through the proper class setter.
        if (this.hasOwnProperty('color')) {
            const value = this.color;
            delete this['color'];
            this.color = value;
        }
        else if (this.color == null) {
            this.color = this.getAttribute('color') || '';
        }
        else if (this[$color]) {
            this[$update](this[$color]);
        }
    }
    handleEvent(event) {
        const target = event.target;
        const { value } = target;
        switch (event.type) {
            case 'input':
                const hex = escape(value);
                this[$saved] = this.color;
                if ((0,_utils_validate_js__WEBPACK_IMPORTED_MODULE_1__.validHex)(hex)) {
                    this.color = hex;
                    this.dispatchEvent(new CustomEvent('color-changed', { bubbles: true, detail: { value: '#' + hex } }));
                }
                break;
            case 'blur':
                if (!(0,_utils_validate_js__WEBPACK_IMPORTED_MODULE_1__.validHex)(value)) {
                    this.color = this[$saved];
                }
        }
    }
    attributeChangedCallback(_attr, _oldVal, newVal) {
        if (this.color !== newVal) {
            this.color = newVal;
        }
    }
    [$update](hex) {
        if (this[$input]) {
            this[$input].value = hex == null || hex == '' ? '' : escape(hex);
        }
    }
}
//# sourceMappingURL=hex-input.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/entrypoints/hex.js":
/*!**************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/entrypoints/hex.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "HexBase": () => (/* binding */ HexBase)
/* harmony export */ });
/* harmony import */ var _components_color_picker_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/color-picker.js */ "./node_modules/vanilla-colorful/lib/components/color-picker.js");
/* harmony import */ var _utils_convert_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/convert.js */ "./node_modules/vanilla-colorful/lib/utils/convert.js");
/* harmony import */ var _utils_compare_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/compare.js */ "./node_modules/vanilla-colorful/lib/utils/compare.js");



const colorModel = {
    defaultColor: '#000',
    toHsva: _utils_convert_js__WEBPACK_IMPORTED_MODULE_0__.hexToHsva,
    fromHsva: _utils_convert_js__WEBPACK_IMPORTED_MODULE_0__.hsvaToHex,
    equal: _utils_compare_js__WEBPACK_IMPORTED_MODULE_1__.equalHex,
    fromAttr: (color) => color
};
class HexBase extends _components_color_picker_js__WEBPACK_IMPORTED_MODULE_2__.ColorPicker {
    get colorModel() {
        return colorModel;
    }
}
//# sourceMappingURL=hex.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/styles/color-picker.js":
/*!******************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/styles/color-picker.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (`:host{display:flex;flex-direction:column;position:relative;width:200px;height:200px;user-select:none;-webkit-user-select:none;cursor:default}:host([hidden]){display:none!important}[role=slider]{position:relative;touch-action:none;user-select:none;-webkit-user-select:none;outline:0}[role=slider]:last-child{border-radius:0 0 8px 8px}[part$=pointer]{position:absolute;z-index:1;box-sizing:border-box;width:28px;height:28px;transform:translate(-50%,-50%);background-color:#fff;border:2px solid #fff;border-radius:50%;box-shadow:0 2px 4px rgba(0,0,0,.2)}[part$=pointer]::after{display:block;content:'';position:absolute;left:0;top:0;right:0;bottom:0;border-radius:inherit;background-color:currentColor}[role=slider]:focus [part$=pointer]{transform:translate(-50%,-50%) scale(1.1)}`);
//# sourceMappingURL=color-picker.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/styles/hue.js":
/*!*********************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/styles/hue.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (`[part=hue]{flex:0 0 24px;background:linear-gradient(to right,red 0,#ff0 17%,#0f0 33%,#0ff 50%,#00f 67%,#f0f 83%,red 100%)}[part=hue-pointer]{top:50%;z-index:2}`);
//# sourceMappingURL=hue.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/styles/saturation.js":
/*!****************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/styles/saturation.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (`[part=saturation]{flex-grow:1;border-color:transparent;border-bottom:12px solid #000;border-radius:8px 8px 0 0;background-image:linear-gradient(to top,#000,transparent),linear-gradient(to right,#fff,rgba(255,255,255,0));box-shadow:inset 0 0 0 1px rgba(0,0,0,.05)}[part=saturation-pointer]{z-index:3}`);
//# sourceMappingURL=saturation.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/utils/compare.js":
/*!************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/utils/compare.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "equalColorObjects": () => (/* binding */ equalColorObjects),
/* harmony export */   "equalColorString": () => (/* binding */ equalColorString),
/* harmony export */   "equalHex": () => (/* binding */ equalHex)
/* harmony export */ });
/* harmony import */ var _convert_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./convert.js */ "./node_modules/vanilla-colorful/lib/utils/convert.js");

const equalColorObjects = (first, second) => {
    if (first === second)
        return true;
    for (const prop in first) {
        // The following allows for a type-safe calling of this function (first & second have to be HSL, HSV, or RGB)
        // with type-unsafe iterating over object keys. TS does not allow this without an index (`[key: string]: number`)
        // on an object to define how iteration is normally done. To ensure extra keys are not allowed on our types,
        // we must cast our object to unknown (as RGB demands `r` be a key, while `Record<string, x>` does not care if
        // there is or not), and then as a type TS can iterate over.
        if (first[prop] !==
            second[prop])
            return false;
    }
    return true;
};
const equalColorString = (first, second) => {
    return first.replace(/\s/g, '') === second.replace(/\s/g, '');
};
const equalHex = (first, second) => {
    if (first.toLowerCase() === second.toLowerCase())
        return true;
    // To compare colors like `#FFF` and `ffffff` we convert them into RGB objects
    return equalColorObjects((0,_convert_js__WEBPACK_IMPORTED_MODULE_0__.hexToRgba)(first), (0,_convert_js__WEBPACK_IMPORTED_MODULE_0__.hexToRgba)(second));
};
//# sourceMappingURL=compare.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/utils/convert.js":
/*!************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/utils/convert.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "hexToHsva": () => (/* binding */ hexToHsva),
/* harmony export */   "hexToRgba": () => (/* binding */ hexToRgba),
/* harmony export */   "parseHue": () => (/* binding */ parseHue),
/* harmony export */   "hslaStringToHsva": () => (/* binding */ hslaStringToHsva),
/* harmony export */   "hslStringToHsva": () => (/* binding */ hslStringToHsva),
/* harmony export */   "hslaToHsva": () => (/* binding */ hslaToHsva),
/* harmony export */   "hsvaToHex": () => (/* binding */ hsvaToHex),
/* harmony export */   "hsvaToHsla": () => (/* binding */ hsvaToHsla),
/* harmony export */   "hsvaToHsvString": () => (/* binding */ hsvaToHsvString),
/* harmony export */   "hsvaToHsvaString": () => (/* binding */ hsvaToHsvaString),
/* harmony export */   "hsvaToHslString": () => (/* binding */ hsvaToHslString),
/* harmony export */   "hsvaToHslaString": () => (/* binding */ hsvaToHslaString),
/* harmony export */   "hsvaToRgba": () => (/* binding */ hsvaToRgba),
/* harmony export */   "hsvaToRgbString": () => (/* binding */ hsvaToRgbString),
/* harmony export */   "hsvaToRgbaString": () => (/* binding */ hsvaToRgbaString),
/* harmony export */   "hsvaStringToHsva": () => (/* binding */ hsvaStringToHsva),
/* harmony export */   "hsvStringToHsva": () => (/* binding */ hsvStringToHsva),
/* harmony export */   "rgbaStringToHsva": () => (/* binding */ rgbaStringToHsva),
/* harmony export */   "rgbStringToHsva": () => (/* binding */ rgbStringToHsva),
/* harmony export */   "rgbaToHex": () => (/* binding */ rgbaToHex),
/* harmony export */   "rgbaToHsva": () => (/* binding */ rgbaToHsva),
/* harmony export */   "roundHsva": () => (/* binding */ roundHsva),
/* harmony export */   "rgbaToRgb": () => (/* binding */ rgbaToRgb),
/* harmony export */   "hslaToHsl": () => (/* binding */ hslaToHsl),
/* harmony export */   "hsvaToHsv": () => (/* binding */ hsvaToHsv)
/* harmony export */ });
/* harmony import */ var _math_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./math.js */ "./node_modules/vanilla-colorful/lib/utils/math.js");

/**
 * Valid CSS <angle> units.
 * https://developer.mozilla.org/en-US/docs/Web/CSS/angle
 */
const angleUnits = {
    grad: 360 / 400,
    turn: 360,
    rad: 360 / (Math.PI * 2)
};
const hexToHsva = (hex) => rgbaToHsva(hexToRgba(hex));
const hexToRgba = (hex) => {
    if (hex[0] === '#')
        hex = hex.substr(1);
    if (hex.length < 6) {
        return {
            r: parseInt(hex[0] + hex[0], 16),
            g: parseInt(hex[1] + hex[1], 16),
            b: parseInt(hex[2] + hex[2], 16),
            a: 1
        };
    }
    return {
        r: parseInt(hex.substr(0, 2), 16),
        g: parseInt(hex.substr(2, 2), 16),
        b: parseInt(hex.substr(4, 2), 16),
        a: 1
    };
};
const parseHue = (value, unit = 'deg') => {
    return Number(value) * (angleUnits[unit] || 1);
};
const hslaStringToHsva = (hslString) => {
    const matcher = /hsla?\(?\s*(-?\d*\.?\d+)(deg|rad|grad|turn)?[,\s]+(-?\d*\.?\d+)%?[,\s]+(-?\d*\.?\d+)%?,?\s*[/\s]*(-?\d*\.?\d+)?(%)?\s*\)?/i;
    const match = matcher.exec(hslString);
    if (!match)
        return { h: 0, s: 0, v: 0, a: 1 };
    return hslaToHsva({
        h: parseHue(match[1], match[2]),
        s: Number(match[3]),
        l: Number(match[4]),
        a: match[5] === undefined ? 1 : Number(match[5]) / (match[6] ? 100 : 1)
    });
};
const hslStringToHsva = hslaStringToHsva;
const hslaToHsva = ({ h, s, l, a }) => {
    s *= (l < 50 ? l : 100 - l) / 100;
    return {
        h: h,
        s: s > 0 ? ((2 * s) / (l + s)) * 100 : 0,
        v: l + s,
        a
    };
};
const hsvaToHex = (hsva) => rgbaToHex(hsvaToRgba(hsva));
const hsvaToHsla = ({ h, s, v, a }) => {
    const hh = ((200 - s) * v) / 100;
    return {
        h: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(h),
        s: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(hh > 0 && hh < 200 ? ((s * v) / 100 / (hh <= 100 ? hh : 200 - hh)) * 100 : 0),
        l: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(hh / 2),
        a: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(a, 2)
    };
};
const hsvaToHsvString = (hsva) => {
    const { h, s, v } = roundHsva(hsva);
    return `hsv(${h}, ${s}%, ${v}%)`;
};
const hsvaToHsvaString = (hsva) => {
    const { h, s, v, a } = roundHsva(hsva);
    return `hsva(${h}, ${s}%, ${v}%, ${a})`;
};
const hsvaToHslString = (hsva) => {
    const { h, s, l } = hsvaToHsla(hsva);
    return `hsl(${h}, ${s}%, ${l}%)`;
};
const hsvaToHslaString = (hsva) => {
    const { h, s, l, a } = hsvaToHsla(hsva);
    return `hsla(${h}, ${s}%, ${l}%, ${a})`;
};
const hsvaToRgba = ({ h, s, v, a }) => {
    h = (h / 360) * 6;
    s = s / 100;
    v = v / 100;
    const hh = Math.floor(h), b = v * (1 - s), c = v * (1 - (h - hh) * s), d = v * (1 - (1 - h + hh) * s), module = hh % 6;
    return {
        r: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)([v, c, b, b, d, v][module] * 255),
        g: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)([d, v, v, c, b, b][module] * 255),
        b: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)([b, b, d, v, v, c][module] * 255),
        a: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(a, 2)
    };
};
const hsvaToRgbString = (hsva) => {
    const { r, g, b } = hsvaToRgba(hsva);
    return `rgb(${r}, ${g}, ${b})`;
};
const hsvaToRgbaString = (hsva) => {
    const { r, g, b, a } = hsvaToRgba(hsva);
    return `rgba(${r}, ${g}, ${b}, ${a})`;
};
const hsvaStringToHsva = (hsvString) => {
    const matcher = /hsva?\(?\s*(-?\d*\.?\d+)(deg|rad|grad|turn)?[,\s]+(-?\d*\.?\d+)%?[,\s]+(-?\d*\.?\d+)%?,?\s*[/\s]*(-?\d*\.?\d+)?(%)?\s*\)?/i;
    const match = matcher.exec(hsvString);
    if (!match)
        return { h: 0, s: 0, v: 0, a: 1 };
    return roundHsva({
        h: parseHue(match[1], match[2]),
        s: Number(match[3]),
        v: Number(match[4]),
        a: match[5] === undefined ? 1 : Number(match[5]) / (match[6] ? 100 : 1)
    });
};
const hsvStringToHsva = hsvaStringToHsva;
const rgbaStringToHsva = (rgbaString) => {
    const matcher = /rgba?\(?\s*(-?\d*\.?\d+)(%)?[,\s]+(-?\d*\.?\d+)(%)?[,\s]+(-?\d*\.?\d+)(%)?,?\s*[/\s]*(-?\d*\.?\d+)?(%)?\s*\)?/i;
    const match = matcher.exec(rgbaString);
    if (!match)
        return { h: 0, s: 0, v: 0, a: 1 };
    return rgbaToHsva({
        r: Number(match[1]) / (match[2] ? 100 / 255 : 1),
        g: Number(match[3]) / (match[4] ? 100 / 255 : 1),
        b: Number(match[5]) / (match[6] ? 100 / 255 : 1),
        a: match[7] === undefined ? 1 : Number(match[7]) / (match[8] ? 100 : 1)
    });
};
const rgbStringToHsva = rgbaStringToHsva;
const format = (number) => {
    const hex = number.toString(16);
    return hex.length < 2 ? '0' + hex : hex;
};
const rgbaToHex = ({ r, g, b }) => {
    return '#' + format(r) + format(g) + format(b);
};
const rgbaToHsva = ({ r, g, b, a }) => {
    const max = Math.max(r, g, b);
    const delta = max - Math.min(r, g, b);
    // prettier-ignore
    const hh = delta
        ? max === r
            ? (g - b) / delta
            : max === g
                ? 2 + (b - r) / delta
                : 4 + (r - g) / delta
        : 0;
    return {
        h: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(60 * (hh < 0 ? hh + 6 : hh)),
        s: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(max ? (delta / max) * 100 : 0),
        v: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)((max / 255) * 100),
        a
    };
};
const roundHsva = (hsva) => ({
    h: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(hsva.h),
    s: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(hsva.s),
    v: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(hsva.v),
    a: (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.round)(hsva.a, 2)
});
const rgbaToRgb = ({ r, g, b }) => ({ r, g, b });
const hslaToHsl = ({ h, s, l }) => ({ h, s, l });
const hsvaToHsv = (hsva) => {
    const { h, s, v } = roundHsva(hsva);
    return { h, s, v };
};
//# sourceMappingURL=convert.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/utils/dom.js":
/*!********************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/utils/dom.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "tpl": () => (/* binding */ tpl),
/* harmony export */   "fire": () => (/* binding */ fire)
/* harmony export */ });
const cache = {};
const tpl = (html) => {
    let template = cache[html];
    if (!template) {
        template = document.createElement('template');
        template.innerHTML = html;
        cache[html] = template;
    }
    return template;
};
const fire = (target, type, detail) => {
    target.dispatchEvent(new CustomEvent(type, {
        bubbles: true,
        detail
    }));
};
//# sourceMappingURL=dom.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/utils/math.js":
/*!*********************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/utils/math.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "clamp": () => (/* binding */ clamp),
/* harmony export */   "round": () => (/* binding */ round)
/* harmony export */ });
// Clamps a value between an upper and lower bound.
// We use ternary operators because it makes the minified code
// 2 times shorter then `Math.min(Math.max(a,b),c)`
const clamp = (number, min = 0, max = 1) => {
    return number > max ? max : number < min ? min : number;
};
const round = (number, digits = 0, base = Math.pow(10, digits)) => {
    return Math.round(base * number) / base;
};
//# sourceMappingURL=math.js.map

/***/ }),

/***/ "./node_modules/vanilla-colorful/lib/utils/validate.js":
/*!*************************************************************!*\
  !*** ./node_modules/vanilla-colorful/lib/utils/validate.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "validHex": () => (/* binding */ validHex)
/* harmony export */ });
const hex3 = /^#?[0-9A-F]{3}$/i;
const hex6 = /^#?[0-9A-F]{6}$/i;
const validHex = (color) => hex6.test(color) || hex3.test(color);
//# sourceMappingURL=validate.js.map

/***/ }),

/***/ "./resources/js/admin/controllers sync recursive \\.ts$":
/*!****************************************************!*\
  !*** ./resources/js/admin/controllers/ sync \.ts$ ***!
  \****************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./admin-structure.ts": "./resources/js/admin/controllers/admin-structure.ts",
	"./color-picker.ts": "./resources/js/admin/controllers/color-picker.ts",
	"./dragon-nest.ts": "./resources/js/admin/controllers/dragon-nest.ts",
	"./permission-grid.ts": "./resources/js/admin/controllers/permission-grid.ts",
	"./slugger.ts": "./resources/js/admin/controllers/slugger.ts"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./resources/js/admin/controllers sync recursive \\.ts$";

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!*************************************!*\
  !*** ./resources/js/admin/index.ts ***!
  \*************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vanilla_colorful__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vanilla-colorful */ "./node_modules/vanilla-colorful/hex-color-picker.js");
/* harmony import */ var vanilla_colorful_hex_input_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vanilla-colorful/hex-input.js */ "./node_modules/vanilla-colorful/hex-input.js");
var context = __webpack_require__("./resources/js/admin/controllers sync recursive \\.ts$");

window.Stimulus.load(context.keys().map(function (key) {
  return {
    identifier: (key.match(/^(?:\.\/)?(.+)(\..+?)$/) || [])[1],
    controllerConstructor: context(key)["default"]
  };
}));


})();

/******/ })()
;