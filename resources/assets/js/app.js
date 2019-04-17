require('./bootstrap')

import Vue2Filters from 'vue2-filters'
import VModal from 'vue-js-modal'
import vSelect from 'vue-select'
import {VClosePopover, VPopover, VTooltip} from 'v-tooltip'

Vue.directive('tooltip', VTooltip)
Vue.directive('close-popover', VClosePopover)
Vue.component('v-popover', VPopover)

Vue.use(Vue2Filters)
Vue.use(VModal)
Vue.use(require('vue-moment'))
Vue.component('v-select', vSelect)

Vue.component('campaign-custom-fields', require('./components/CampaignSelectCustomFields.vue'))
Vue.component('campaign-data-scope', require('./components/CampaignDataScope.vue'))
Vue.component('campaign-confirmation-scope', require('./components/CampaignConfirmationScope.vue'))
Vue.component('campaign-invalid-reasons', require('./components/CampaignInvalidReasons.vue'))
Vue.component('campaign-users', require('./components/CampaignUsers.vue'))
Vue.component('custom-fields', require('./components/CustomFields.vue'))
Vue.component('import-leads', require('./components/ImportLeads.vue'))
Vue.component('upload-leads', require('./components/UploadLeads.vue'))
Vue.component('ip-black-white-list', require('./components/IpBlackWhiteList.vue'))

Vue.component('file-upload', require('./utilities/FileUpload.vue'))
Vue.component('font-awesome', require('./utilities/FontAwesome.vue'))

// Vue.component('campaign-custom-fields', require('./components/CampaignCustomFields.vue'))

const app = new Vue({
    el: '#app',
})
