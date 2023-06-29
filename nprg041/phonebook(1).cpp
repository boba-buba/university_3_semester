#include "phonebook.hpp"

phonebook::person_handle_list phonebook::find(
	dept_filter p_dept,
	const std::string& s_dept,
	last_name_filter p_last_name,
	const std::string& s_last_name)
{
	person_handle_list b;

	switch (p_dept)
	{
		case phonebook::any_dept:
		{
			switch (p_last_name)
			{
				case phonebook::any_last_name:
				{
					// iterating over all names - creates an alphabetical-ordered person_handle_list
					for (auto it = this->name_database.begin();
						it != this->name_database.end();
						it++)
					{
						// iterating over all persons with same last_name
						for (auto name_it = it->second.begin();
							name_it != it->second.end();
							name_it++)
						{
							b.push_back(*name_it);
						}
					}
					break;
				}
				case phonebook::last_name_prefix: // using the fact, that last_name database is ordered!!
				{
					for (auto it = this->name_database.lower_bound(s_last_name); // starting from the lower_bound (O (logn))
						it != this->name_database.end();
						it++)
					{
						if (it->first.substr(0, s_last_name.size()) > s_last_name) // prefix cannot be smaller than the base
							// also base must be shorted to prefix.length
							break;
						if (contains_given_prefix(it->first, s_last_name)) // if prefix included in the name
						{
							for (auto name_it = it->second.begin();
								name_it != it->second.end();
								name_it++)
							{
								b.push_back(*name_it);
							}
						}
					}
					break;
				}
				default:
				{
					throw new std::invalid_argument("Unknown phonebook::find parameter");
					break;
				}
			}
			break;
		}
		case phonebook::dept_equal:
		{
			auto search_result = this->dept_database.find(s_dept);
			if (search_result != this->dept_database.end())
			{
				switch (p_last_name)
				{
					case phonebook::any_last_name:
					{
						// iterating over all last_name buckets
						for (auto bucket_it = search_result->second.begin();
							bucket_it != search_result->second.end();
							bucket_it++)
						{
							for (auto nested_it = bucket_it->second.begin(); // iterating over all handles with the same last_name
								nested_it != bucket_it->second.end();
								nested_it++)
							{
								b.push_back(*nested_it);
							}
						}
						break;
					}
					case phonebook::last_name_prefix:
					{
						for (auto bucket_it = search_result->second.lower_bound(s_last_name);
							bucket_it != search_result->second.end();
							bucket_it++)
						{
							if (contains_given_prefix(bucket_it->first, s_last_name)) // when last_name contains given prefix
							{
								for (auto name_it = bucket_it->second.begin(); // iterating over all handles with the same last_name
									name_it < bucket_it->second.end();
									name_it++)
								{
									b.push_back(*name_it);
								}
							}
						}
						break;
					}
					default:
					{
						throw new std::invalid_argument("Unknown phonebook::find parameter");
						break;
					}
				}
			}
			break;
		}
		case phonebook::dept_substring:
		{
			for (auto dept_it = this->dept_database.begin();
				dept_it != this->dept_database.end();
				dept_it++)
			{
				if (strstr(dept_it->first.c_str(), s_dept.c_str())) // when substring of a dept.
				{
					switch (p_last_name)
					{
						case phonebook::any_last_name:
						{
							for (auto bucket_it = dept_it->second.begin();
								bucket_it != dept_it->second.end();
								bucket_it++)
							{
								for (auto name_it = bucket_it->second.begin();
									name_it != bucket_it->second.end();
									name_it++)
								{
									b.push_back(*name_it);
								}
								
							}
							break;
						}
							
						case phonebook::last_name_prefix:
						{
							for (auto bucket_it = dept_it->second.lower_bound(s_last_name);
								bucket_it != dept_it->second.end();
								bucket_it++)
							{
								if (contains_given_prefix(bucket_it->first, s_last_name))
								{
									for (auto name_it = bucket_it->second.begin();
										name_it != bucket_it->second.end();
										name_it++)
									{
										b.push_back(*name_it);
									}
								}
							}
							break;
						}
						default:
						{
							throw new std::invalid_argument("Unknown phonebook::find parameter");
							break;
						}
					}
				}
			}
			break;
		}
		default:
		{
			throw new std::invalid_argument("Unknown phonebook::find parameter");
			break;
		}
	}
	
	return b;
}

bool phonebook::contains_given_prefix(std::string base, const std::string& prefix)
{
	std::size_t length_of_base_string = base.length();
	std::size_t length_of_prefix = prefix.length();

	if (length_of_base_string < length_of_prefix)
		return false; // automatically false, if prefix longer

	for (size_t i = 0; i < length_of_prefix && i < length_of_base_string; i++)
	{
		if (base[i] != prefix[i]) // when spotted a diff, can already return FALSE
			return false;
	}

	return true; // If ended up here, all chars had to match, therefore prefix contained
}

bool phonebook::existence_check(phonebook::person_handle z) // ~O(log n) time complexity
{
	auto search_result = this->name_database.find(z->last_name);
	if (search_result != this->name_database.end())
	{
		for (auto it = search_result->second.begin();
			it != search_result->second.end(); 
			it++)
		{
			if ((*it) == z)
				return true;
		}
	}
	return false;
}

const phonebook::person_entry& phonebook::get_person_entry(person_handle h) const // ~O(log |dept| ) time complexity
{
	auto dept_candidate = this->dept_database.find(h->dept); // O(1)
	if (dept_candidate != this->dept_database.end()) // find() returns set.end() when NOT FOUND
	{
		auto name_candidate = dept_candidate->second.find(h->last_name); // O (log n)
		if (name_candidate != dept_candidate->second.end())
		{
			for (auto name_it = name_candidate->second.begin();
				name_it != name_candidate->second.end();
				name_it++)
			{
				if ((*name_it) == h)
					return *name_it->get();
			}
		}
	}
	throw new std::invalid_argument("Invalid person_handle given.");
}

void phonebook::add_person_name_database(phonebook::person_handle z) // O(log n) time complexity
{
	std::string last_name = z->last_name;
	auto search_result = this->name_database.find(last_name);

	if (search_result != this->name_database.end())
		// when name already in database, add new person_handle to the vector
		search_result->second.push_back(std::move(z));
	else
		// else create a new vector for given name
		this->name_database.insert({ last_name, person_handle_list {std::move(z)} });
}

void phonebook::remove_person_name_database(phonebook::person_handle z) // ~O(log n) time complexity
{
	std::string last_name = z->last_name;
	auto search_result = this->name_database.find(last_name);

	// iterating over all same last_names
	for (auto it = search_result->second.begin();
		it < search_result->second.end();
		it++)
	{
		if ((*it) == z) // when found the old name person_handle
		{
			search_result->second.erase(it); // remove the handle from the vector
			if (search_result->second.size() == 0) // when last handle was removed
				this->name_database.erase(last_name);
			return;
		}
	}
	throw new std::invalid_argument("Invalid person_handle given.");
}

void phonebook::add_person_dept_database(phonebook::person_handle z) // O(log n) time complexity
{
	std::string dept = z->dept;
	std::string last_name = z->last_name;

	auto search_result = this->dept_database.find(dept);
	if (search_result != this->dept_database.end()) // when bucket with dept already exists
	{
		auto nested_search_result = search_result->second.find(last_name); 
		if (nested_search_result != search_result->second.end()) // when nested bucket with name already exist
			nested_search_result->second.push_back(std::move(z));
		else
			search_result->second.insert({ last_name, person_handle_list {std::move(z)} });
	}
	else // else make new dept bucket and insert new last_name bucket
	{
		auto iterator = this->dept_database.insert({ dept, sorted_person_handle_lists {} }); // new dept bucket
		iterator.first->second.insert({ last_name, person_handle_list{ std::move(z) } }); // new last_name bucket
	}
		
}

void phonebook::remove_person_dept_database(phonebook::person_handle z) // O(log n) time complexity
{
	std::string dept = z->dept;
	std::string last_name = z->last_name;

	auto search_result = this->dept_database.find(dept);
	if (search_result != this->dept_database.end())
	{
		auto nested_search_result = search_result->second.find(last_name);
		if (nested_search_result != search_result->second.end())
		{
			for (auto it = nested_search_result->second.begin(); // iterating over all same last_names
				it != nested_search_result->second.end();
				it++)
			{
				if ((*it) == z)
				{
					nested_search_result->second.erase(it);
					if (nested_search_result->second.size() == 0)
						search_result->second.erase(last_name);
					if (search_result->second.size() == 0)
						this->dept_database.erase(dept);
					return;
				}
			}
		}
	}
	throw new std::invalid_argument("Invalid person_handle given.");
}


phonebook::person_handle phonebook::add_person(const person_entry& z) // O(log n) time complexity
{
	phonebook::person_handle new_person_handle = std::make_shared<person_entry>(z);
	this->add_person_name_database(new_person_handle);
	this->add_person_dept_database(new_person_handle);
	return new_person_handle;
}

void phonebook::add_phone(person_handle h, const std::string& phone)
{
	if (!existence_check(h)) // If handle has been removed
		throw new std::invalid_argument("Invalid person_handle given.");

	for (auto it = h->phones.begin();
		it != h->phones.end();
		++it)
	{
		if (*it == phone) // when phone already exists
			return;
	}
	h->phones.push_back(phone); // when reached this line, phone was not in the list yet, therefore adding into
}

void phonebook::remove_phone(person_handle h, const std::string& phone)
{
	if (!existence_check(h)) // If handle has been removed
		throw new std::invalid_argument("Invalid person_handle given.");

	for (auto it = h->phones.begin();
		it != h->phones.end();
		++it)
	{
		if (*it == phone) // when found the phone
		{
			h->phones.erase(it);
		}
	}
}

void phonebook::change_last_name(person_handle h, const std::string& last_name) // ~O(log n) time complexity
{
	// remove the old handle
	remove_person_dept_database(h);
	remove_person_name_database(h);

	// change the last name
	h->last_name = last_name;
	
	// add the modified handle
	add_person_dept_database(h);
	add_person_name_database(h);
}

void phonebook::change_dept(person_handle h, const std::string& dept) // O(|dept|) time complexity
{
	// remove the old handle
	remove_person_dept_database(h);

	//change the dept
	h->dept = dept;

	// add the modified handle
	add_person_dept_database(h);
}

void phonebook::remove_person(person_handle h) // O( max{ O(|dept|), O(log n) } time complexity
{
	remove_person_dept_database(h);
	remove_person_name_database(h);
}

/**/
