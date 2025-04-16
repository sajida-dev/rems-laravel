<template>
    <section class="ftco-section ftco-agent-details py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- Tab Navigation -->
            <div class="flex justify-center mb-8">
                <ul class="flex gap-4 border-b" role="tablist">
                    <li>
                        <button :class="tab === 'personal' ? activeTabClass : tabClass" @click="tab = 'personal'">
                            Personal Info
                        </button>
                    </li>
                    <li>
                        <button :class="tab === 'properties' ? activeTabClass : tabClass" @click="tab = 'properties'">
                            Properties
                        </button>
                    </li>
                    <li>
                        <button :class="tab === 'contact' ? activeTabClass : tabClass" @click="tab = 'contact'">
                            Contact Info
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div>
                <AgentPersonalInfo v-if="tab === 'personal'" :agent="agent" :hireLink="hireLink" />
                <AgentProperties v-else-if="tab === 'properties'" :properties="agent.properties" />
                <AgentContactInfo v-else-if="tab === 'contact'" :agent="agent" />
            </div>
        </div>
    </section>
</template>

<script>
import AgentPersonalInfo from './AgentPersonalInfo.vue';
import AgentProperties from './AgentProperties.vue';
import AgentContactInfo from './AgentContactInfo.vue';

export default {
    name: "AgentTabs",
    components: {
        AgentPersonalInfo,
        AgentProperties,
        AgentContactInfo
    },

    props: {
        agent: {
            type: Object,
            required: true,
        },
        hireLink: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            tab: "personal",
        };
    },
    computed: {
        tabClass() {
            return "px-4 py-2 text-gray-700 hover:text-pink-500 border-b-2 border-transparent focus:outline-none";
        },
        activeTabClass() {
            return "px-4 py-2 text-pink-400 border-b-2 border-pink-400 focus:outline-none";
        },
    },
};


</script>
