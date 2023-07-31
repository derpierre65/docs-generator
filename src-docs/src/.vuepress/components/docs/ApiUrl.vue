<template>
	<div>
		<Badge :type="badgeColor" :text="type.toUpperCase()" vertical="top" />
		<code>{{endpoint}}</code>
	</div>
</template>

<script>
export default {
	name: 'ApiUrl',
	props: {
		type: {
			type: String,
			default: '',
		},
		endpoint: {
			type: String,
			default: '',
		},
	},
	computed: {
		badgeColor() {
			if (this.type === 'GET') {
				return 'notice';
			}
			else if (this.type === 'POST') {
				return 'tip';
			}
			else if (this.type === 'PATCH') {
				return 'warning';
			}
			else if (this.type === 'DELETE') {
				return 'danger';
			}
		},
	},
};
</script>