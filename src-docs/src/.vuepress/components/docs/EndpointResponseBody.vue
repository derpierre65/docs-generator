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
		<tr v-if="hasPagination">
			<td>data</td>
			<td>Object[]</td>
			<td>The list of resources.</td>
		</tr>
		<template v-for="field in fields">
			<tr >
				<td :class="{'pl-6': hasPagination}">{{field.name}} <RequiredBadge v-if="field.required" /></td>
				<td>{{field.type}}</td>
				<td>{{field.description}}</td>
			</tr>
			<template v-if="field.fields">
				<tr v-for="subField in field.fields">
					<td :class="{'pl-8': hasPagination}">{{subField.name}} <RequiredBadge v-if="subField.required" /></td>
					<td>{{subField.type}}</td>
					<td>{{subField.description}}</td>
				</tr>
			</template>
		</template>
		<template v-if="hasPagination">
			<tr>
				<td>meta</td>
				<td>Object</td>
				<td>The information used to page through the list of results. The object is empty if there are no more pages left to page through.</td>
			</tr>
			<tr>
				<td class="pl-6">current_page</td>
				<td>Number</td>
				<td>The number of current page</td>
			</tr>
			<tr>
				<td class="pl-6">last_page</td>
				<td>Number</td>
				<td>The number of last page</td>
			</tr>
			<tr>
				<td class="pl-6">per_page</td>
				<td>Number</td>
				<td>The number of results per page.</td>
			</tr>
			<tr>
				<td class="pl-6">total</td>
				<td>Number</td>
				<td>The number of total results.</td>
			</tr>
		</template>
		</tbody>
	</table>
</template>

<script>
import RequiredBadge from './RequiredBadge.vue';

export default {
	name: 'EndpointResponseBody',
	components: { RequiredBadge },
	props: {
		hasPagination: Boolean,
		fields: {
			type: Object,
			default: () => ({}),
		},
	}
};
</script>