<template>
  <div>
		<section class="gc-tabs-user-entries">
			<p v-if="userId === 0">You must be logged in to see this page</p>
			<div v-if="userEntries && userEntries.length === 1">
        <!-- <p>Submissions for The US Open Championship are now closed! Good luck!</p> -->
				<p v-if="saved"><strong>Entry Saved!</strong></p>
				<div class="gc-tabs-user-info" v-if="currentContest"><p>Please edit or submit for The Grand Slam Challenge - {{ currentContest.name }}</p><a :href="worksheet_link" target="_blank">Worksheet</a></div>
			</div>
			<div v-if="userId != 0 && (userEntries && userEntries.length > 1)">
				<p v-if="saved"><strong>Entry Saved!</strong></p>
				<div class="gc-tabs-user-info" v-if="currentContest"><p>Please edit or submit for The Grand Slam Challenge - {{ currentContest.name }}</p><a :href="worksheet_link" target="_blank">Worksheet</a></div>
				<p v-if="currentContest">Additionally, you can see a summary of your picks below.</p>
				<select v-model="selectedEntry" v-if="userEntries.length > 1 && userId != 0">
					<option v-for="entry in userEntries" v-if="entry.contest_id == currentContest.id" :value="entry" :key="entry.id">{{ entry.name }}</option>
				</select>
			</div>
      <div v-if="userId != 0 && userEntries && userEntries.length == 0">Sorry, registration for this contest is closed! Check back next year</div>
			<!-- <div v-if="userId != 0 && userEntries && userEntries.length == 0">Sorry, registration for this contest is closed! Check back next year</div> -->
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
								<p @click="addGolfer(golfer, 1, i)" v-if="golfer.name != editingEntry.tier1 && golfer.name != editingEntry.tier6" v-html="(golfer.rank + ' ' + golfer.name)"></p>
								<p v-else class="-null">{{golfer.rank}} {{ golfer.name }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier2'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier2" :key="i">
								<p @click="addGolfer(golfer, 2, i)" v-if="golfer.name != editingEntry.tier2 && golfer.name != editingEntry.tier6" v-html="(golfer.rank + ' ' + golfer.name)"></p>
								<p v-else class="-null">{{golfer.rank}} {{ golfer.name }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier3'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier3" :key="i">
								<p @click="addGolfer(golfer, 3, i)" v-if="golfer.name != editingEntry.tier3 && golfer.name != editingEntry.tier6" v-html="(golfer.rank + ' ' + golfer.name)"></p>
								<p v-else class="-null">{{golfer.rank}} {{ golfer.name }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier4'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier4" :key="i">
								<p @click="addGolfer(golfer, 4, i)" v-if="golfer.name != editingEntry.tier4 && golfer .name!= editingEntry.tier6" v-html="(golfer.rank + ' ' + golfer.name)"></p>
								<p v-else class="-null">{{golfer.rank}} {{ golfer.name }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier5'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in golfers.tier5" :key="i">
								<p @click="addGolfer(golfer, 5, i)" v-if="golfer.name != editingEntry.tier5 && golfer != editingEntry.tier6.name" v-html="(golfer.rank + ' ' + golfer.name)"></p>
								<p v-else class="-null">{{golfer.rank}} {{ golfer.name }}</p>
							</li>
						</ul>
					</section>
					<section v-if="activeTier == 'tier6'">
						<ul class="gc-tabs-golfers">
							<li v-for="(golfer, i) in filteredTier6" :key="i">
								<p @click="addGolfer(golfer, 6, i)" v-if="golfer.name != editingEntry.tier6 && golfer.name != editingEntry.tier6" v-html="(golfer.rank + ' ' + golfer.name)"></p>
								<p v-else class="-null">{{golfer.rank}} {{golfer.name}}</p>
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
				v-if="submissionReady"
				@click="submitEntry()">Submit/Update Picks</div>
		<div v-if="userEntries && userEntries.length > 1" class="gc-tabs-submissions">
			<h4>Your Submitted Picks</h4>
			<div class="gc-tabs-submissions-single" v-if="entry.tier1.length != 0" v-for="(entry, i) in userEntries" :key="i">
				<p><strong>{{ entry.name }} </strong></p>
				<ul>
					<li>
						<p><strong>Tier 1: </strong>{{ entry.tier1 }}</p>
					</li>
					<li>
						<p><strong>Tier 2: </strong>{{ entry.tier2 }}</p>
					</li>
					<li>
						<p><strong>Tier 3: </strong>{{ entry.tier3 }}</p>
					</li>
					<li>
						<p><strong>Tier 4: </strong>{{ entry.tier4 }}</p>
					</li>
					<li>
						<p><strong>Tier 5: </strong>{{ entry.tier5 }}</p>
					</li>
					<li>
						<p><strong>Tier 6: </strong>{{ entry.tier6 }}</p>
					</li>
				</ul>
			</div>
		</div>
  </div>
</template>

<script>
import _ from 'lodash';
import moment from '../vendor/moment.min.js';

export default {
  name: "Golfers",

  data() {
  	return {
			activeTier: 'tier1',
			golfers: {
        tier1: [],
        tier2: [],
        tier3: [],
        tier4: [],
        tier5: [],
				tier6: []
			},
			editingEntry: null,
			selectedEntry: null,
			entryName: null,
			userId: theUser.userid,
			user: theUser,
			userEntries: null,
			editingEntry: null,
			contests: [],
			currentContest: null,
			now: moment().utc().format(),
      saved: false,
      worksheet_link: undefined
  	}
	},

	mounted() {
		if (this.userId != 0) {
			this.getContests();
			this.getGolfersTest();
		}
	},

	computed: {
		filteredTier6() {
      let tierNames = [];
      _.map(this.golfers, tier => {
        _.map(tier, golfer => {
          tierNames.push(golfer);
        })
      })
			let tier6 = tierNames;
			return _.pullAllBy(tier6, [{'name': this.editingEntry.tier1}, {name: this.editingEntry.tier2}, {name: this.editingEntry.tier3}, {name: this.editingEntry.tier4}, {name: this.editingEntry.tier5}], 'name');
    },

    submissionReady() {
      if (this.editingEntry &&
        ((this.editingEntry.tier1 ) &&
        (this.editingEntry.tier2 ) &&
        (this.editingEntry.tier3 ) &&
        (this.editingEntry.tier4 ) &&
        (this.editingEntry.tier5 ) &&
        (this.editingEntry.tier6 ))) {
          return true;
      } else {
        return false;
      }
    }
	},

	watch: {
		selectedEntry() {
			this.editingEntry = {};
			this.editingEntry = JSON.parse(JSON.stringify(this.selectedEntry));
		},

		userEntries() {
			if (this.userEntries.length == 1) {

				this.editingEntry = {};
        this.editingEntry = JSON.parse(JSON.stringify(this.userEntries[0]));

			}
		}
	},

  methods: {
		getContests() {
			axios.get(`https://gupscorner.com/wp-json/contests/v1/all`)
				.then(r => {
					this.contests = r.data;
					this.filterContests();
				})
		},

		filterContests() {
			let upcomingContests = [];
			this.contests.forEach(contest => {
				if (this.now < contest.close) {
					upcomingContests.push(contest);
				}
			})

			this.setCurrentContest(upcomingContests);
		},

		setCurrentContest(upcoming) {
      let sorted = _.orderBy(upcoming, 'close', 'asc');
      console.log(sorted);
			this.currentContest = upcoming[0];
			this.getUserEntries();
		},

		getUserEntries() {
			// if (this.userId.length) {
        axios.get(`https://gupscorner.com/wp-json/contests/v1/contest/3/users/${this.userId}`)
				// axios.get(`https://gupscorner.com/wp-json/contests/v1/contest/${this.currentContest.id}/users/${this.userId}`)
					.then(r => {
						this.userEntries = r.data;
					})
			// }
		},

		setEditingEntry(entry) {
			this.editingEntry = {};
      this.editingEntry = JSON.parse(JSON.stringify(entry));
		},

		getGolfersTest() {
			axios.get(`https://gupscorner.com/wp-json/acf/v3/pages/3089`)
				.then(r => {
          this.worksheet_link = r.data.acf.worksheet_link;

					let arr1 = r.data.acf.tier1.split(',');
					let arr2 = r.data.acf.tier2.split(',');
					let arr3 = r.data.acf.tier3.split(',');
					let arr4 = r.data.acf.tier4.split(',');
          let arr5 = r.data.acf.tier5.split(',');

          let matcher = /\d{1,3}.\s/mg;

          arr1.forEach(golfer => {
            let newGolfer = {rank: golfer.match(matcher)[0], name: golfer.split(matcher)[1]};
            this.golfers.tier1.push(newGolfer);
          });

          arr2.forEach(golfer => {
            let newGolfer = {rank: golfer.match(matcher)[0], name: golfer.split(matcher)[1]};
            this.golfers.tier2.push(newGolfer);
          });

          arr3.forEach(golfer => {
            let newGolfer = {rank: golfer.match(matcher)[0], name: golfer.split(matcher)[1]};
            this.golfers.tier3.push(newGolfer);
          });

          arr4.forEach(golfer => {
            let newGolfer = {rank: golfer.match(matcher)[0], name: golfer.split(matcher)[1]};
            this.golfers.tier4.push(newGolfer);
          });

          arr5.forEach(golfer => {
            let newGolfer = {rank: golfer.match(matcher)[0], name: golfer.split(matcher)[1]};
            this.golfers.tier5.push(newGolfer);
          });
				})
		},

		addGolfer(golfer, tier, i) {

			switch(tier) {
				case 1:
					this.$set(this.editingEntry, 'tier1', golfer.name);
					break;

				case 2:
					this.$set(this.editingEntry, 'tier2', golfer.name);
					break;

				case 3:
					this.$set(this.editingEntry, 'tier3', golfer.name);
					break;

				case 4:
					this.$set(this.editingEntry, 'tier4', golfer.name);
					break;

				case 5:
					this.$set(this.editingEntry, 'tier5', golfer.name);
					break;

				case 6:
					this.$set(this.editingEntry, 'tier6', golfer.name);
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

			axios.patch(`https://gupscorner.com/wp-json/contests/v1/entries/${this.editingEntry.entry_id}`, entryInfo)
				.then(r => {
					this.editingEntry = null;
					this.getUserEntries();
					this.saved = true;
					this.flashSave();
				})
		},

		flashSave() {
			let t = this;
			setTimeout(function() {
				t.saved = false;
			}, 10000)
		}
  }
};
</script>

