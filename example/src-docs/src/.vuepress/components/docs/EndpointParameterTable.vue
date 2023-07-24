<template>
	<table>
		<thead>
		<tr>
			<th>Parameter</th>
			<th>Type</th>
			<th>Description</th>
		</tr>
		</thead>
		<tbody>
		<tr v-for="field of fields">
			<td>{{field.name}} <RequiredBadge v-if="field.required" /></td>
			<td>{{field.type}}</td>
			<td>{{field.description}}</td>
		</tr>
		</tbody>
	</table>
</template>

<script>
import RequiredBadge from './RequiredBadge.vue';

export default {
	name: 'EndpointParameterTable',
	components: { RequiredBadge },
	props: {
		fields: {
			type: Object,
			default: () => ({}),
		},
	},
};
</script>