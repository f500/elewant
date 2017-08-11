Welcome to the Herding domain
-----------------------------

Here, we start with a `Herd`. A `Herd` is managed by a shepherd, who is represented here by nothing
more than a `ShepherdId`.

A herd consists of `ElePHPants`. When a herd accepts a new ElePHPant, that is called `Adopting`. When
a shepherd removes an ElePHPant from a herd, that is called `Abandoning`. If a shepherd decides to end 
a herd, that entire herd is `Abandoned`.

Every `ElePHPant` is a certain `Breed`. When looking at a Herd, we can look at all it's ElePHPants, but
also at every _unique_ Breed in that Herd, and the total number of ElePHPants of each Breed. 

So when dealing with herds, we currently have the following command (event):

- FormHerd (HerdWasFormed)
- AdoptElePHPant (ElePHPantWasAdoptedByHerd)
- AbandonElePHPant (ElePHPantWasAbandonedByHerd)
- AbandonHerd (HerdWasAbandoned)

Decisions
---------

2017-08-01 Herd management is different from trading. A separate `Trading` domain will be used when we startg implementing 
trades.
