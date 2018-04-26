<template>
  <div>
		<section class="gc-tabs-user-entries">
			<div v-if="userEntries && userEntries.length > 0">
				<p>Choose an entry to edit or submit for {{ currentContest.name }}</p>
				<select v-model="selectedEntry">
					<option v-for="entry in userEntries" v-if="entry.contest_id == currentContest.id" :value="entry" :key="entry.id">{{ entry.name }}</option>
				</select>
			</div>
			<div v-if="userEntries && userEntries.length == 0">Sorry, registration for this contest is closed! Check back next year</div>
		</section>
		<section class="gc-tabs" v-if="editingEntry">
			<div class="gc-tabs-content">
				<ul class="gc-tabs-links">
					<li @click="activeTier = 'tier1'" :class="activeTier == 'tier1' ? '-active' : ''">
						<span>Tier 1</span>
					</li>
					<li @click="activeTier = 'tier2'" :class="activeTier == 'tier2' ? '-active' : ''">
						<span>Tier 2</span>
					</li>
					<li @click="activeTier = 'tier3'" :class="activeTier == 'tier3' ? '-active' : ''">
						<span>Tier 3</span>
					</li>
					<li @click="activeTier = 'tier4'" :class="activeTier == 'tier4' ? '-active' : ''">
						<span>Tier 4</span>
					</li>
					<li @click="activeTier = 'tier5'" :class="activeTier == 'tier5' ? '-active' : ''">
						<span>Tier 5</span>
					</li>
					<li @click="activeTier = 'tier6'" :class="activeTier == 'tier6' ? '-active' : ''">
						<span>Tier 6</span>
					</li>
				</ul>
				<div class="gc-tabs-tiers">
					<section v-if="activeTier == 'tier1'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier1" :key="i">
								<p @click="addGolfer(golfer, 1, i)" v-if="golfer != editingEntry.tier1 && golfer != editingEntry.tier6">{{ golfer }}</p>
								<p v-else class="-null">{{ golfer }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier2'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier2" :key="i">
								<p @click="addGolfer(golfer, 2, i)" v-if="golfer != editingEntry.tier2 && golfer != editingEntry.tier6">{{ golfer }}</p>
								<p v-else class="-null">{{ golfer }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier3'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier3" :key="i">
								<p @click="addGolfer(golfer, 3, i)" v-if="golfer != editingEntry.tier3 && golfer != editingEntry.tier6">{{ golfer }}</p>
								<p v-else class="-null">{{ golfer }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier4'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier4" :key="i">
								<p @click="addGolfer(golfer, 4, i)" v-if="golfer != editingEntry.tier4 && golfer != editingEntry.tier6">{{ golfer }}</p>
								<p v-else class="-null">{{ golfer }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier5'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier5" :key="i">
								<p @click="addGolfer(golfer, 5, i)" v-if="golfer != editingEntry.tier5 && golfer != editingEntry.tier6">{{ golfer }}</p>
								<p v-else class="-null">{{ golfer }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier6'">
						<ul class="gc-tabs-golfers" v-for="(golfer, i) in filteredTier6" :key="i">
							<li>
								<p @click="addGolfer(golfer, 6, i)" v-if="golfer != editingEntry.tier6 && golfer != editingEntry.tier6">{{ golfer }}</p>
								<p v-else class="-null">{{ golfer }}</p>
							</li>
						</ul>
					</section>
				</div>
			</div>
			<div class="gc-tabs-picks">
				<p class="gc-tabs-picks-title">Your Picks</p>
				<div class="gc-tabs-picks-list">
					<div class="gc-tabs-picks-item">
						<p><strong>Tier 1:</strong> {{ editingEntry.tier1 }}</p>
						<span @click="removePick(1, editingEntry.tier1)" v-if="editingEntry.tier1">Remove</span>
					</div>
					<div class="gc-tabs-picks-item">
						<p><strong>Tier 2:</strong> {{ editingEntry.tier2 }}</p>
						<span @click="removePick(2, editingEntry.tier2)" v-if="editingEntry.tier2">Remove</span>
					</div>
					<div class="gc-tabs-picks-item">
						<p><strong>Tier 3:</strong> {{ editingEntry.tier3 }}</p>
						<span @click="removePick(3, editingEntry.tier3)" v-if="editingEntry.tier3">Remove</span>
					</div>
					<div class="gc-tabs-picks-item">
						<p><strong>Tier 4:</strong> {{ editingEntry.tier4 }}</p>
						<span @click="removePick(4, editingEntry.tier4)" v-if="editingEntry.tier4">Remove</span>
					</div>
					<div class="gc-tabs-picks-item">
						<p><strong>Tier 5:</strong> {{ editingEntry.tier5 }}</p>
						<span @click="removePick(5, editingEntry.tier5)" v-if="editingEntry.tier5">Remove</span>
					</div>
					<div class="gc-tabs-picks-item">
						<p><strong>Tier 6:</strong> {{ editingEntry.tier6 }}</p>
						<span @click="removePick(6, editingEntry.tier6)" v-if="editingEntry.tier6">Remove</span>
					</div>
				</div>
			</div>
		</section>
		<div class="gc-tabs-submit" 
				v-if="editingEntry && 
						(editingEntry.tier1.length &&
						editingEntry.tier2.length &&
						editingEntry.tier3.length &&
						editingEntry.tier4.length &&
						editingEntry.tier5.length &&
						editingEntry.tier6.length)" 
				@click="submitEntry()">Submit/Update Picks</div>
  </div>
</template>

<script>
import _ from 'lodash';
import moment from '../vendor/moment.min.js';

export default {
  name: "Golfers",
  props: ['tier'],

  data() {
  	return {
			activeTier: 'tier1',
			golfers: {
				tier6: []
			},
			editingEntry: null,
			selectedEntry: null,
			entryName: null,
			userId: theUser.userid,
			// userId: 1,
			userEntries: null,
			editingEntry: null,
			updatedTier1: [],
			updatedTier2: [],
			updatedTier3: [],
			updatedTier4: [],
			updatedTier5: [],
			updatedTier6: [],
			contests: [
				{
					name: 'The Players Championship',
					starts: moment('2018/05/09 07:00').utc().format(),
					id: 2
				},
				{
					name: 'The US Open',
					starts: moment('2018/06/14').utc().format(),
					id: 3
				},
				{
					name: 'The Open',
					starts: '2018/07/19',
					id: 4
				},
				{
					name: 'The PGA Championship',
					starts: '2018/08/09',
					id: 5
				}
			],
			currentContest: {
				name: 'The Players Championship',
				starts: '2018/05/09',
				id: 2
			}
  	}
	},
	
	created() {
		this.getUserEntries();
		this.getGolfersTest();
		console.log(this.editingEntry)
	},

	computed: {
		filteredTier6() {
			let tier6 = _.concat(this.golfers.tier1, this.golfers.tier2, this.golfers.tier3, this.golfers.tier4, this.golfers.tier5);
			return _.pull(tier6, this.editingEntry.tier1, this.editingEntry.tier2, this.editingEntry.tier3, this.editingEntry.tier4, this.editingEntry.tier5);
		},

		filteredContest() {
			// let upcomingContest = _.pull(contests, )
		}
	},

	watch: {
		selectedEntry() {
			this.editingEntry = {};
			this.editingEntry = JSON.parse(JSON.stringify(this.selectedEntry));
		}
	},

  methods: {
		getUserEntries() {
			// if (this.userId.length) {
				axios.get(`http://l95.91e.myftpupload.com/wp-json/contests/v1/users/${this.userId}`)
					.then(r => {
						this.userEntries = r.data;
					})
			// }
		},

		setEditingEntry(entry) {
			console.log('settings: ', entry)
			this.editingEntry = {};
			this.editingEntry = JSON.parse(JSON.stringify(entry));
		},

		getGolfersTest() {
			axios.get(`http://l95.91e.myftpupload.com/wp-json/acf/v3/pages/2399`)
				.then(r => {

					let arr1 = r.data.acf.tier1.split(',');
					let arr2 = r.data.acf.tier2.split(',');
					let arr3 = r.data.acf.tier3.split(',');
					let arr4 = r.data.acf.tier4.split(',');
					let arr5 = r.data.acf.tier5.split(',');
					
					this.$set(this.golfers, 'tier1', arr1);
					this.$set(this.golfers, 'tier2', arr2);
					this.$set(this.golfers, 'tier3', arr3);
					this.$set(this.golfers, 'tier4', arr4);
					this.$set(this.golfers, 'tier5', arr5);
				}) 
		},

		addGolfer(golfer, tier, i) {
			
			switch(tier) {
				case 1:
					this.$set(this.editingEntry, 'tier1', golfer);
					break;

				case 2:					
					this.$set(this.editingEntry, 'tier2', golfer);
					break;
				
				case 3:
					this.$set(this.editingEntry, 'tier3', golfer);
					break;

				case 4:
					this.$set(this.editingEntry, 'tier4', golfer);
					break;
				
				case 5:
					this.$set(this.editingEntry, 'tier5', golfer);
					break;

				case 6:
					this.$set(this.editingEntry, 'tier6', golfer);
					break;
			}
		},

		removePick(tier, pick) {
			
			switch(tier) {
				case 1:				
					this.$set(this.editingEntry, 'tier1', '');
					break;

				case 2:
					this.$set(this.editingEntry, 'tier2', '');
					break;
				
				case 3:
					this.$set(this.editingEntry, 'tier3', '');
					break;

				case 4:
					this.$set(this.editingEntry, 'tier4', '');
					break;
				
				case 5:
					this.$set(this.editingEntry, 'tier5', '');
					break;

				case 6:
					this.$set(this.editingEntry, 'tier6', '');
					break;
			}
		},

		submitEntry() {
			let entryInfo = {
				tier1: this.editingEntry.tier1,
				tier2: this.editingEntry.tier2,
				tier3: this.editingEntry.tier3,
				tier4: this.editingEntry.tier4,
				tier5: this.editingEntry.tier5,
				tier6: this.editingEntry.tier6
			}

			axios.patch(`http://l95.91e.myftpupload.com/wp-json/contests/v1/contest/${this.currentContest.id}/entries/${this.editingEntry.entry_id}`, entryInfo)
				.then(r => {
					this.editingEntry = null;
					this.getUserEntries();
				})
		}
  }
};
</script>

